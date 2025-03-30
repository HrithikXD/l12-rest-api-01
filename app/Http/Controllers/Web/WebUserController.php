<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class WebUserController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        // You can set this in your .env file
        $this->apiBaseUrl = env('API_BASE_URL', 'http://127.0.0.1:8000/api');
    }

    public function showLoginForm()
    {
        if (Session::has('api_token') && Session::has('user')) {
            return redirect()->route('tasks.index');
        }
        return view('user.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $response = Http::post($this->apiBaseUrl . '/users/login', $credentials);

            if ($response->successful()) {
                $data = $response->json();
                // Store the token in session
                Session::put('api_token', $data['token']);
                // Store user data if needed
                $response = Http::withToken(Session::get('api_token'))
                    ->get($this->apiBaseUrl . '/users/profile');
                $user = $response->json(['data']);
                Session::put('user', $user);
                return redirect()->route('tasks.index')->with('success', 'Login Successful');
            } else {
                $responseData = $response->json();

                // Check if we have validation errors in the expected format
                if (isset($responseData['errors']) && is_array($responseData['errors'])) {
                    return back()->withErrors($responseData['errors'])->withInput($request->except('password'));
                }
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput($request->except('password'));
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'API connection error: ' . $e->getMessage(),
            ])->withInput($request->except('password'));
        }
    }

    public function showRegistrationForm()
    {
        if (Session::has('api_token') && Session::has('user')) {
            return redirect()->route('tasks.index');
        }
        return view('user.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            $response = Http::post($this->apiBaseUrl . '/users/register', [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            if ($response->json(['errors'])) {
                $responseData = $response->json();

                // Check if we have validation errors in the expected format
                if (isset($responseData['errors']) && is_array($responseData['errors'])) {
                    return back()->withErrors($responseData['errors'])->withInput($request->except('password'));
                }
                return back()->withErrors([
                    'message' => 'Registration failed: ' . json_encode($response->json()),
                ])->withInput($request->except('password', 'password_confirmation'));
            } else {
                $responseData = $response->json();
                // Store the token in session
                Session::put('api_token', $responseData['token']);
                // Store user data
                Session::put('user', $responseData['user']);

                return redirect()->route('tasks.index')->with('success', 'Registration Successful');
            }
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'API connection error: ' . $e->getMessage(),
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function logout()
    {
        $token = Session::get('api_token');

        if ($token) {
            try {
                // Call the API logout endpoint
                Http::withToken($token)->post($this->apiBaseUrl . '/users/logout');
            } catch (\Exception $e) {
                // Log error but continue with local logout
            }
        }

        // Clear session data regardless of API response
        Session::forget('api_token');
        Session::forget('user');

        return redirect()->route('login')->with('success', 'Logout Successful');
    }

    public function showProfile()
    {
        if (Session::has('user')) {
            $user = Session::get('user');
            return view('user.profile', compact('user'));
        }

        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        try {
            // Get the authenticated user's profile from API
            $response = Http::withToken($token)
                ->get($this->apiBaseUrl . '/users/profile');

            if ($response->successful()) {
                $user = $response->json(['data']);
                Session::put('user', $user);
                return view('user.profile', compact('user'));
            }

            // If token is invalid, redirect to login
            return redirect()->route('login');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load profile: ' . $e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:50',
            'email' => 'sometimes|email',
        ]);

        try {
            $response = Http::withToken($token)
                ->put($this->apiBaseUrl . '/users/profile/update', $data);

            if ($response->successful()) {

                if ($response->json(['errors'])) {
                    return back()->with([
                        'message' => 'Failed to update profile: ' . json_encode($response->json(['message'])),
                    ]);
                }

                $user = $response->json(['data']);
                // Update session data
                Session::put('user', $user);

                return back()->with('success', 'Profile updated successfully');
            }

            return back()->withErrors([
                'message' => 'Failed to update profile: ' . json_encode($response->json()),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'API connection error: ' . $e->getMessage(),
            ]);
        }
    }

    public function index()
    {
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)
                ->get($this->apiBaseUrl . '/users');

            if ($response->successful()) {
                $users = $response->json();
                return view('user.index', compact('users'));
            }

            return back()->with('error', 'Failed to load users');
        } catch (\Exception $e) {
            return back()->with('error', 'API connection error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)
                ->get($this->apiBaseUrl . '/users/' . $id);

            if ($response->successful()) {
                $user = $response->json();
                $response = Http::withToken($token)
                    ->get($this->apiBaseUrl . '/tasks/admin/' . $id);

                $taskData = $response->json();

                return view('user.show', [
                    'user' => $user['data'] ?? [],
                    'tasks' => $taskData['data'] ?? []
                ]);
            }

            return back()->with('error', 'User not found');
        } catch (\Exception $e) {
            return back()->with('error', 'API connection error: ' . $e->getMessage());
        }
    }

    public function updateUser(Request $request, $id)
    {
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:50',
            'email' => 'sometimes|email',
            'is_admin' => 'sometimes|boolean',
        ]);
        // dd($data);
        try {
            $response = Http::withToken($token)
                ->put($this->apiBaseUrl . '/users/' . $id, $data);

            // dd($response); // This will dump the response and stop execution

            if ($response->successful()) {

                if ($response->json(['errors'])) {
                    return back()->with([
                        'message' => 'Failed to update profile: ' . json_encode($response->json()),
                    ]);
                }

                return back()->with('success', 'Profile updated successfully');
            }

            return back()->withErrors([
                'message' => 'Failed to update profile: ' . json_encode($response->json()),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'API connection error: ' . $e->getMessage(),
            ]);
        }
    }
}
