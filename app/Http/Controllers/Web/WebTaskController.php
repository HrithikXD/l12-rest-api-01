<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class WebTaskController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        // You can set this in your .env file
        $this->apiBaseUrl = env('API_BASE_URL', 'http://127.0.0.1:8000/api');
    }

    public function index()
    {
        // if (Session::has('tasks')) {
        //     $tasks = Session::get('tasks');
        //     return view('task.index', compact('tasks'));
        // }
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        try {
            // Get the authenticated user's profile from API
            $response = Http::withToken($token)
                ->get($this->apiBaseUrl . '/tasks/user');

            if ($response->successful()) {
                $tasks = $response->json(['data']);
                Session::put('tasks', $tasks);
                return view('task.index', compact('tasks'));
            }

            // If token is invalid, redirect to login
            return redirect()->route('login');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load profile: ' . $e->getMessage());
        }
    }

    public function show($id){
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        try {
            $response = Http::withToken($token)
                ->get($this->apiBaseUrl . '/tasks/' . $id);

            if ($response->successful()) {
                $task = $response->json(['data']);
                Session::put('task', $task);
                return view('task.show', compact('task'));
            }

            return back()->with('error', 'User not found');
        } catch (\Exception $e) {
            return back()->with('error', 'API connection error: ' . $e->getMessage());
        }
    }

    public function create(){
        return view('task.create');
    }

    public function edit(){
        $task = Session::get('task');
        return view('task.edit', compact('task'));

    }

    public function update(Request $request, $id){
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:50',
            'description' => 'sometimes|string',
            'long_description' => 'sometimes|string'
        ]);
        try {
            $response = Http::withToken($token)
                ->put($this->apiBaseUrl . '/tasks/' . $id, $data);

            if ($response->successful()) {

                if ($response->json(['errors'])) {
                    return back()->with([
                        'message' => 'Failed to update task: ' . json_encode($response->json()),
                    ]);
                }

                return redirect()->route('tasks.show', $id)->with('success', 'Task updated successfully');
            }

            return back()->withErrors([
                'message' => 'Failed to update task: ' . json_encode($response->json()),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'API connection error: ' . $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request){
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:50',
            'description' => 'sometimes|string',
            'long_description' => 'sometimes|string'
        ]);

        try {
            $response = Http::withToken($token)
                ->post($this->apiBaseUrl . '/tasks', $data);

            if ($response->successful()) {

                if ($response->json(['errors'])) {
                    return back()->with([
                        'message' => 'Failed to update profile: ' . json_encode($response->json()),
                    ]);
                }
                $data = $response->json(['data']);
                return redirect()->route('tasks.show', $data['id'])->with('success', 'Task added successfully');
            }

            return back()->withErrors([
                'message' => 'Failed to crate task: ' . json_encode($response->json()),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'API connection error: ' . $e->getMessage(),
            ]);
        }
    }

    public function delete()
    {
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        $task = Session::get('task');
        try {
            $response = Http::withToken($token)
                ->delete($this->apiBaseUrl . '/tasks/' . $task['id']);

            if ($response->successful()) {

                if ($response->json(['errors'])) {
                    return back()->with([
                        'message' => 'Failed to delete task: ' . json_encode($response->json()),
                    ]);
                }

                $tid = $task['user_id'];
                $uid = Session::get('user')['id'];

                if($tid != $uid){
                    return redirect()->route('users.show', $tid)->with('success', 'Task deleted successfully');
                }
                return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
            }

            return back()->withErrors([
                'message' => 'Failed to delete task: ' . json_encode($response->json()),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'API connection error: ' . $e->getMessage(),
            ]);
        }
    }

    public function complete(){

        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login');
        }

        $task = Session::get('task');

        try {
            // dd($task);
            $response = Http::withToken($token)
                ->put($this->apiBaseUrl . '/tasks/' . $task['id'], ['completed'=>!$task['completed']]);

            if ($response->successful()) {

                if ($response->json(['errors'])) {
                    return back()->with([
                        'message' => 'Failed to update task status: ' . json_encode($response->json()),
                    ]);
                }

                return redirect()->route('tasks.show', $task['id'])->with('success', 'Task status updated successfully');
            }

            return back()->withErrors([
                'message' => 'Failed to update task status: ' . json_encode($response->json()),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'API connection error: ' . $e->getMessage(),
            ]);
        }
    }

}
