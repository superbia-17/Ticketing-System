<div x-data="tracker()" x-init="getTicket()">
    <template x-if="ticket">
        <div class="p-4 border rounded shadow">
            <h3 x-text="ticket.ticket_number" class="font-mono text-lg"></h3> [cite: 134]
            <p><strong>Status:</strong> <span x-text="ticket.status"></span></p> [cite: 136]
            
            <div class="mt-4">
                <h4 class="font-bold">Conversation</h4>
                <template x-for="res in ticket.public_responses"> [cite: 139]
                    <div class="bg-gray-100 p-2 my-2 rounded">
                        <p x-text="res.message"></p> [cite: 140]
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>

<script>
function tracker() {
    return {
        ticket: null,
        async getTicket() {
            const ticketNumber = new URLSearchParams(window.location.search).get('number');
            const res = await fetch(`/api/tickets/track/${ticketNumber}`, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('auth_token') } [cite: 126]
            });
            this.ticket = await res.json();
        }
    }
}
</script>