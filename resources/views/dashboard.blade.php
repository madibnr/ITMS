<x-app-layout>
    {{-- ─── Stats Cards ─── --}}
    <div class="stats-grid">
        {{-- Open Tickets --}}
        <div class="stat-card animate-fade-in animate-fade-in-delay-1">
            <div class="stat-card-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                </svg>
            </div>
            <div class="stat-card-info">
                <div class="stat-card-label">Open Tickets</div>
                <div class="stat-card-value animate-count-up">{{ $stats['open_tickets'] }}</div>
                @if($stats['overdue_tickets'] > 0)
                    <div class="stat-card-trend down">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z" />
                        </svg>
                        {{ $stats['overdue_tickets'] }} overdue
                    </div>
                @else
                    <div class="stat-card-trend up">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        No overdue
                    </div>
                @endif
            </div>
        </div>

        {{-- Total Assets --}}
        <div class="stat-card animate-fade-in animate-fade-in-delay-2">
            <div class="stat-card-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-1.243 1.007-2.25 2.25-2.25h13.5" />
                </svg>
            </div>
            <div class="stat-card-info">
                <div class="stat-card-label">Total Assets</div>
                <div class="stat-card-value animate-count-up">{{ $stats['total_assets'] }}</div>
                <div class="stat-card-trend up">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                    {{ $stats['active_assets'] }} active
                </div>
            </div>
        </div>

        {{-- Open Incidents --}}
        <div class="stat-card animate-fade-in animate-fade-in-delay-3">
            <div class="stat-card-icon red">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <div class="stat-card-info">
                <div class="stat-card-label">Open Incidents</div>
                <div class="stat-card-value animate-count-up">{{ $stats['open_incidents'] }}</div>
                <div class="stat-card-trend {{ $stats['open_incidents'] > 0 ? 'down' : 'up' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ $stats['upcoming_maintenance'] }} upcoming maintenance
                </div>
            </div>
        </div>

        {{-- SLA Compliance --}}
        <div class="stat-card animate-fade-in animate-fade-in-delay-4">
            <div class="stat-card-icon teal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
            <div class="stat-card-info">
                <div class="stat-card-label">SLA Compliance</div>
                <div class="stat-card-value animate-count-up">{{ $slaPerformance['compliance_rate'] }}%</div>
                <div class="stat-card-trend {{ $slaPerformance['compliance_rate'] >= 80 ? 'up' : 'down' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $slaPerformance['compliance_rate'] >= 80 ? 'M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18' : 'M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3' }}" />
                    </svg>
                    {{ $slaPerformance['met_sla'] }} of {{ $slaPerformance['total'] }} met
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Panels Row ─── --}}
    <div class="panel-grid">
        {{-- SLA Performance --}}
        <div class="panel animate-fade-in animate-fade-in-delay-3">
            <div class="panel-header">
                <div class="panel-title">SLA Performance</div>
                <a href="{{ route('reports.tickets') }}" class="panel-subtitle">View Report →</a>
            </div>
            <div class="panel-body">
                <div class="progress-bar-container">
                    <div class="progress-bar-label">
                        <span>Compliance Rate</span>
                        <strong>{{ $slaPerformance['compliance_rate'] }}%</strong>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $slaPerformance['compliance_rate'] }}%"></div>
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <div class="metric-row">
                        <span class="metric-label">Total Resolved</span>
                        <span class="metric-value primary">{{ $slaPerformance['total'] }}</span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label">
                            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; margin-right: 8px;"></span>
                            Met SLA
                        </span>
                        <span class="metric-value green">{{ $slaPerformance['met_sla'] }}</span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label">
                            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ef4444; margin-right: 8px;"></span>
                            Breached SLA
                        </span>
                        <span class="metric-value red">{{ $slaPerformance['breached_sla'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Asset Summary --}}
        <div class="panel animate-fade-in animate-fade-in-delay-4">
            <div class="panel-header">
                <div class="panel-title">Asset Summary</div>
                @if(auth()->user()->hasAnyRole(['admin', 'it-staff']))
                    <a href="{{ route('assets.index') }}" class="panel-subtitle">View All →</a>
                @endif
            </div>
            <div class="panel-body">
                {{-- Visual bar chart for assets --}}
                <div style="display: flex; align-items: flex-end; gap: 24px; height: 120px; margin-bottom: 20px; padding: 0 20px;">
                    @php
                        $maxVal = max($assetSummary['active'], $assetSummary['in_repair'], $assetSummary['retired'], 1);
                    @endphp
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                        <div style="width: 100%; max-width: 48px; height: {{ max(($assetSummary['active'] / $maxVal) * 100, 8) }}%; background: linear-gradient(180deg, #10b981, #34d399); border-radius: 8px 8px 4px 4px; transition: height 1s ease;"></div>
                        <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Active</span>
                        <span style="font-size: 16px; font-weight: 700; color: #059669;">{{ $assetSummary['active'] }}</span>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                        <div style="width: 100%; max-width: 48px; height: {{ max(($assetSummary['in_repair'] / $maxVal) * 100, 8) }}%; background: linear-gradient(180deg, #f59e0b, #fbbf24); border-radius: 8px 8px 4px 4px; transition: height 1s ease;"></div>
                        <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">In Repair</span>
                        <span style="font-size: 16px; font-weight: 700; color: #d97706;">{{ $assetSummary['in_repair'] }}</span>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                        <div style="width: 100%; max-width: 48px; height: {{ max(($assetSummary['retired'] / $maxVal) * 100, 8) }}%; background: linear-gradient(180deg, #94a3b8, #cbd5e1); border-radius: 8px 8px 4px 4px; transition: height 1s ease;"></div>
                        <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Retired</span>
                        <span style="font-size: 16px; font-weight: 700; color: var(--text-muted);">{{ $assetSummary['retired'] }}</span>
                    </div>
                </div>

                <div class="metric-row" style="border-top: 1px solid var(--border-color); padding-top: 14px;">
                    <span class="metric-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; display: inline; vertical-align: text-bottom; color: #d97706; margin-right: 6px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.249-8.25-3.286Z" />
                        </svg>
                        Warranty Expiring (30d)
                    </span>
                    <span class="metric-value orange">{{ $assetSummary['warranty_expiring'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Recent Tickets ─── --}}
    <div class="panel animate-fade-in animate-fade-in-delay-5" style="margin-bottom: 28px;">
        <div class="panel-header" style="padding-bottom: 0;">
            <div>
                <div class="panel-title">Recent Tickets</div>
                <div class="panel-subtitle" style="cursor: default;">Latest Support Requests</div>
            </div>
            <a href="{{ route('tickets.index') }}" class="panel-subtitle">View All →</a>
        </div>
        <div class="panel-body" style="padding-top: 12px;">
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ticket Number</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTickets as $ticket)
                        <tr>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="link-primary" style="font-weight: 600;">
                                    {{ $ticket->ticket_number }}
                                </a>
                            </td>
                            <td style="color: var(--text-primary); font-weight: 500;">
                                {{ Str::limit($ticket->title, 45) }}
                            </td>
                            <td>
                                <span class="badge badge-{{ strtolower($ticket->priority) }}">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ strtolower(str_replace(' ', '-', $ticket->status)) }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td style="color: var(--text-secondary);">
                                @php
                                    $creatorName = $ticket->creator->name ?? $ticket->reporter->full_name ?? '-';
                                @endphp
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, {{ $ticket->source === 'public' ? '#fef3c7, #fde68a' : '#e0e7ff, #c7d2fe' }}); display: flex; align-items: center; justify-content: center; color: {{ $ticket->source === 'public' ? '#92400e' : 'var(--primary)' }}; font-size: 11px; font-weight: 600; flex-shrink: 0;">
                                        {{ strtoupper(substr($creatorName, 0, 2)) }}
                                    </div>
                                    {{ $creatorName }}
                                    @if($ticket->source === 'public')
                                        <span style="font-size: 10px; background: #fef3c7; color: #92400e; padding: 1px 6px; border-radius: 8px; font-weight: 600;">Public</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px 16px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" style="width: 40px; height: 40px; margin: 0 auto 10px; display: block; color: #cbd5e1;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                </svg>
                                No tickets yet. Create your first ticket!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
