<?php

namespace App\Http\Controllers;


use App\Bid;
use App\Events\BidCreated;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\Bid as BidResource;

/**
 * Class BidController
 *
 * @package App\Http\Controllers
 * @group Bid Management
 */
class BidController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param Task $task
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Task $task)
    {
        $this->authorize('index', [Bid::class, $task]);

        return BidResource::collection($task->bids);
    }

    public function show()
    {

    }

    /**
     * Create a bid
     *
     * @bodyParam price string required
     * @bodyParam comment string required
     *
     * @param Request $request
     * @param Task    $task
     *
     * @return BidResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Task $task)
    {
        $this->validate($request, $this->rules());

        $data = $this->extractInputFromRules($request, $this->rules());

        $this->authorize('store', [Bid::class, $task]);

        $bid = $task->bid($data['price'], $data['comment']);

        event(new BidCreated($bid));

        return new BidResource($bid);
    }

    public function update()
    {

    }

    public function destroy()
    {

    }

    private function rules()
    {
        return [
            'price' => 'numeric|required',
            'comment' => 'string|required'
        ];
    }
}
