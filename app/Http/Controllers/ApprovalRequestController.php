<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Services\ApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApprovalRequestController extends Controller
{
    public function approve(Request $request, ApprovalRequest $approvalRequest, ApprovalService $approvals): RedirectResponse
    {
        $request->validate(['review_notes' => ['nullable', 'string', 'max:1000']]);
        $approvals->approve($approvalRequest, $request->user(), $request->input('review_notes'));

        return redirect()->route('dashboard', ['page' => 'approvals'])
            ->with('success', 'Permintaan disetujui dan perubahan sudah diterapkan.');
    }

    public function reject(Request $request, ApprovalRequest $approvalRequest, ApprovalService $approvals): RedirectResponse
    {
        $data = $request->validate(['review_notes' => ['nullable', 'string', 'max:1000']]);
        $approvals->reject($approvalRequest, $request->user(), $data['review_notes'] ?? null);

        return redirect()->route('dashboard', ['page' => 'approvals'])
            ->with('success', 'Permintaan ditolak.');
    }
}
