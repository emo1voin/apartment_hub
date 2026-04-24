<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Services\ApiService;
use App\Services\AuthService;
use Illuminate\Http\Request;

class UserHotelController extends Controller
{
    public function __construct(
        private ApiService $api,
        private AuthService $auth
    ) {}

    public function index()
    {
        $userId = $this->auth->id();
        $hotels = Hotel::where('owner_id', $userId)->latest()->paginate(10);
        return view('user.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('user.hotels.create');
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'description', 'short_description', 'address', 'city', 'country', 'phone', 'email', 'stars']);
        $hasFile = $request->hasFile('main_image');
        if ($hasFile) $data['main_image'] = $request->file('main_image');

        $result = $this->api->post('my/hotels', $data, $hasFile);

        if (!empty($result['success'])) {
            return redirect()->route('user.hotels.index')->with('success', 'Дом отправлен на модерацию!');
        }
        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка создания')->withInput();
    }

    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('user.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'description', 'short_description', 'address', 'city', 'country', 'phone', 'email', 'stars']);
        $hasFile = $request->hasFile('main_image');
        if ($hasFile) $data['main_image'] = $request->file('main_image');

        $result = $this->api->put("my/hotels/{$id}", $data, $hasFile);

        if (!empty($result['success'])) {
            return redirect()->route('user.hotels.index')->with('success', 'Дом обновлён и отправлен на модерацию!');
        }
        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка обновления')->withInput();
    }

    public function destroy($id)
    {
        $result = $this->api->delete("my/hotels/{$id}");
        if (!empty($result['success'])) {
            return redirect()->route('user.hotels.index')->with('success', 'Дом удалён.');
        }
        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка удаления');
    }
}
