<?php

namespace App\Http\Controllers;

use App\Enums\LiveStatus;
use App\Http\Requests\StoreLiveSessionRequest;
use App\Http\Requests\UpdateLiveSessionRequest;
use App\Models\LiveSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Actions\Lives\CancelLiveAction;
use App\Actions\Lives\FinishLiveAction;
use App\Actions\Lives\StartLiveAction;
use RuntimeException;
use App\Enums\ProductStatus;
use App\Models\Product;

class LiveSessionController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'status' => [
                'nullable',
                Rule::enum(LiveStatus::class),
            ],
        ]);

        $status = $request->input('status');

        $liveSessions = LiveSession::query()
            ->withCount('liveProducts')
            ->when(
                $status,
                fn($query) =>
                $query->where('status', $status)
            )
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view(
            'lives.index',
            compact(
                'liveSessions',
                'status'
            )
        );
    }

    public function create(): View
    {
        return view('lives.create');
    }

    public function store(
        StoreLiveSessionRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $data['status'] = LiveStatus::SCHEDULED;
        $data['started_at'] = null;
        $data['ended_at'] = null;

        $liveSession = LiveSession::create($data);

        return redirect()
            ->route('lives.show', $liveSession)
            ->with(
                'success',
                'Live creado correctamente.'
            );
    }

    public function show(
        LiveSession $liveSession
    ): View {
        $liveSession->load([
            'liveProducts.product',
        ]);

        $eligibleProducts = collect();

        if (
            in_array(
                $liveSession->status,
                [
                    LiveStatus::SCHEDULED,
                    LiveStatus::ACTIVE,
                ],
                true
            )
        ) {
            $associatedProductIds = $liveSession
                ->liveProducts
                ->pluck('product_id');

            $eligibleProducts = Product::query()
                ->where(
                    'status',
                    ProductStatus::AVAILABLE
                )
                ->whereNotIn(
                    'id',
                    $associatedProductIds
                )
                ->orderBy('name')
                ->get();
        }

        return view(
            'lives.show',
            compact(
                'liveSession',
                'eligibleProducts'
            )
        );
    }

    public function edit(
        LiveSession $liveSession
    ): View|RedirectResponse {
        if (
            $liveSession->status
            !== LiveStatus::SCHEDULED
        ) {
            return redirect()
                ->route(
                    'lives.show',
                    $liveSession
                )
                ->with(
                    'error',
                    'Solo los Lives programados pueden editarse.'
                );
        }

        return view(
            'lives.edit',
            compact('liveSession')
        );
    }

    public function update(
        UpdateLiveSessionRequest $request,
        LiveSession $liveSession
    ): RedirectResponse {
        if (
            $liveSession->status
            !== LiveStatus::SCHEDULED
        ) {
            return redirect()
                ->route(
                    'lives.show',
                    $liveSession
                )
                ->with(
                    'error',
                    'Solo los Lives programados pueden editarse.'
                );
        }

        $liveSession->update(
            $request->validated()
        );

        return redirect()
            ->route(
                'lives.show',
                $liveSession
            )
            ->with(
                'success',
                'Live actualizado correctamente.'
            );
    }
    public function start(
        LiveSession $liveSession,
        StartLiveAction $action
    ): RedirectResponse {
        try {
            $action->execute($liveSession);

            return redirect()
                ->route('lives.show', $liveSession)
                ->with(
                    'success',
                    'Live iniciado correctamente.'
                );
        } catch (RuntimeException $exception) {

            return redirect()
                ->route('lives.show', $liveSession)
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }
    public function finish(
        LiveSession $liveSession,
        FinishLiveAction $action
    ): RedirectResponse {
        try {
            $action->execute($liveSession);

            return redirect()
                ->route('lives.show', $liveSession)
                ->with(
                    'success',
                    'Live finalizado correctamente.'
                );
        } catch (RuntimeException $exception) {

            return redirect()
                ->route('lives.show', $liveSession)
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }
    public function cancel(
        LiveSession $liveSession,
        CancelLiveAction $action
    ): RedirectResponse {
        try {
            $action->execute($liveSession);

            return redirect()
                ->route('lives.show', $liveSession)
                ->with(
                    'success',
                    'Live cancelado correctamente.'
                );
        } catch (RuntimeException $exception) {

            return redirect()
                ->route('lives.show', $liveSession)
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }
}
