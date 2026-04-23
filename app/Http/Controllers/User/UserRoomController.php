<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Services\ApiService;
use App\Services\AuthService;
use Illuminate\Http\Request;

class UserRoomController extends Controller
{
    public function __construct(
        private ApiService $api,
        private AuthService $auth
    ) {}

    public function index()
    {
        $userId = $this->auth->id();
        $rooms = Room::whereHas('hotel', fn($q) => $q->where('owner_id', $userId))
            ->with('hotel')->latest()->paginate(10);
        $hotels = Hotel::where('owner_id', $userId)->get();
        return view('user.rooms.index', compact('rooms', 'hotels'));
    }

    public function create()
    {
        // Админ видит все дома, обычный пользователь - только свои
        if ($this->auth->isAdmin()) {
            $hotels = Hotel::all();
        } else {
            $hotels = Hotel::where('owner_id', $this->auth->id())->get();
        }
        return view('user.rooms.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['hotel_id', 'name', 'description', 'type', 'price_per_night', 'capacity_adults', 'capacity_children']);
        $hasFile = $request->hasFile('main_image');
        if ($hasFile) $data['main_image'] = $request->file('main_image');

        $result = $this->api->post('my/rooms', $data, $hasFile);

        if (!empty($result['success'])) {
            return redirect()->route('user.rooms.index')->with('success', 'Квартира отправлена на модерацию!');
        }
        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка создания')->withInput();
    }

    public function edit($id)
    {
        $room = Room::with('hotel')->findOrFail($id);
        $hotels = Hotel::where('owner_id', $this->auth->id())->approved()->get();
        return view('user.rooms.edit', compact('room', 'hotels'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'description', 'type', 'price_per_night', 'capacity_adults', 'capacity_children']);
        $hasFile = $request->hasFile('main_image');
        if ($hasFile) $data['main_image'] = $request->file('main_image');

        $result = $this->api->put("my/rooms/{$id}", $data, $hasFile);

        if (!empty($result['success'])) {
            return redirect()->route('user.rooms.index')->with('success', 'Квартира обновлена!');
        }
        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка обновления')->withInput();
    }

    public function destroy($id)
    {
        $result = $this->api->delete("my/rooms/{$id}");
        if (!empty($result['success'])) {
            return redirect()->route('user.rooms.index')->with('success', 'Квартира удалена.');
        }
        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка удаления');
    }
}
