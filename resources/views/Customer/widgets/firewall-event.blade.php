<div class="card shadow-sm border-0 mb-4 bg-dark text-light">
    <div id="firewall-error-msg" class="alert alert-danger mx-3 mt-3 d-none border-0"
        style="background: rgba(220,53,69,0.15); color: #ea5455;"></div>
    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;" id="firewall-events-list">
        <div class="text-center py-4 text-muted">
            <div class="spinner-border spinner-border-sm text-info mb-1"></div>
            <p class="mb-0 small">Loading firewall events...</p>
        </div>
    </div>
</div>

<style>
    #firewall-events-list::-webkit-scrollbar {
        width: 6px;
    }

    #firewall-events-list::-webkit-scrollbar-track {
        background: #1e1e2f;
    }

    #firewall-events-list::-webkit-scrollbar-thumb {
        background: #393953;
        border-radius: 4px;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const list = document.getElementById('firewall-events-list'), errBox = document.getElementById('firewall-error-msg');

        fetch("{{ route('widget.firewall-events') }}").then(r => r.json()).then(res => {
            if (!res.success) throw new Error(res.message || 'Koneksi Server Wazuh Terputus');
            if (!res.data || res.data.length === 0) {
                list.innerHTML = `<div class="text-center py-5 text-muted"><i class="fas fa-check-circle text-success mb-1 fa-2x"></i><p class="mb-0 small">No suspicious logs found.</p></div>`;
                return;
            }

            const styles = { red: { b: 'bg-danger text-danger', i: 'fa-bug' }, yellow: { b: 'bg-warning text-warning', i: 'fa-exclamation-triangle' }, default: { b: 'bg-primary text-primary', i: 'fa-info-circle' } };
            list.innerHTML = res.data.map(ev => {
                const cfg = styles[ev.color] || styles.default;
                return `
                    <div class="p-3 border-bottom border-secondary d-flex justify-content-between align-items-start gap-2" style="background: #1a1a27;">
                        <div class="d-flex gap-2" style="max-width: 85%;">
                            <i class="fas ${cfg.i} ${cfg.b.split(' ')[1]} mt-1"></i>
                            <div>
                                <h6 class="mb-1 text-white-50 small font-weight-bold">Tag: <span class="text-info">${ev.title}</span></h6>
                                <p class="mb-0 text-muted small" style="background: #13131f; padding: 6px 10px; border-radius: 4px; border-left: 3px solid #393953; word-break: break-all;">${ev.description}</p>
                            </div>
                        </div>
                        <span class="badge bg-opacity-10 ${cfg.b} font-weight-bold" style="font-size: 0.65rem;">${ev.severity}</span>
                    </div>`;
            }).join('');
        }).catch(err => {
            errBox.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> ${err.message}`;
            errBox.classList.remove('d-none');
            list.innerHTML = `<div class="text-center py-5 text-danger"><i class="fas fa-wifi-slash mb-1 text-muted fa-2x"></i><p class="mb-0 small font-weight-bold">Offline</p></div>`;
        });
    });
</script>