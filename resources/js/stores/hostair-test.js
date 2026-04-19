import { defineStore } from 'pinia';

export const useHostairTestStore = defineStore('hostair-test', {
    state: () => ({
        testUser: 'Candidato/a',
        bookings: [],
        guests: [], // New: independent guests list
        loading: false,
        error: null,
        currentPage: 1,
        itemsPerPage: 12,
        totalItems: 0,
    }),

    actions: {
        async getBookings() {
            try {
                this.loading = true;
                this.error = null;
                
                const response = await fetch('/api/bookings');
                if (!response.ok) throw new Error('Failed to fetch bookings');
                
                this.bookings = await response.json();
                this.totalItems = this.bookings.length;
            } catch (err) {
                this.error = err.message;
            } finally {
                this.loading = false;
            }
        },

        async getGuests() {
            try {
                this.loading = true;
                this.error = null;
                
                const response = await fetch('/api/guests');
                if (!response.ok) throw new Error('Failed to fetch guests');
                
                this.guests = await response.json();
            } catch (err) {
                this.error = err.message;
            } finally {
                this.loading = false;
            }
        },

        setPage(page) {
            this.currentPage = page;
        },

        setItemsPerPage(items) {
            this.itemsPerPage = items;
            this.currentPage = 1;
        },

        async createGuest(guestData) {
            try {
                this.loading = true;
                this.error = null;

                const response = await fetch('/api/guests', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(guestData),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to create guest');
                }

                const result = await response.json();
                
                // Add to local guests list
                this.guests.push(result.guest);
                
                // If booking_id was provided, the attach happened automatically in backend
                // Refresh bookings to show updated guest lists
                await this.getBookings();

                return result;
            } catch (err) {
                this.error = err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },

        async attachGuestToBooking(guestId, bookingId) {
            try {
                this.error = null;
                const response = await fetch(`/api/guests/${guestId}/bookings/${bookingId}`, {
                    method: 'POST',
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to attach guest');
                }

                const result = await response.json();
                
                // Refresh bookings and guests to reflect changes
                await Promise.all([this.getBookings(), this.getGuests()]);
                
                return result;
            } catch (err) {
                this.error = err.message;
                throw err;
            }
        },

        async detachGuestFromBooking(guestId, bookingId) {
            try {
                this.error = null;
                const response = await fetch(`/api/guests/${guestId}/bookings/${bookingId}`, {
                    method: 'DELETE',
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to detach guest');
                }

                const result = await response.json();
                
                // Refresh bookings and guests
                await Promise.all([this.getBookings(), this.getGuests()]);
                
                return result;
            } catch (err) {
                this.error = err.message;
                throw err;
            }
        },

        async updateGuest(id, guestData) {
            try {
                this.loading = true;
                this.error = null;

                const response = await fetch(`/api/guests/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(guestData),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to update guest');
                }

                const result = await response.json();
                
                // Update guest in local list
                const index = this.guests.findIndex(g => g.id === id);
                if (index !== -1) {
                    this.guests[index] = result.guest;
                }
                
                // Also refresh bookings to update any displayed guest info
                await this.getBookings();

                return result;
            } catch (err) {
                this.error = err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },

        async deleteGuest(id) {
            try {
                this.loading = true;
                this.error = null;

                const response = await fetch(`/api/guests/${id}`, {
                    method: 'DELETE',
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to delete guest');
                }

                // Remove from local guests list
                this.guests = this.guests.filter(g => g.id !== id);
                
                // Refresh bookings to remove from those lists too
                await this.getBookings();

                return { message: 'Guest deleted successfully' };
            } catch (err) {
                this.error = err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },
    },

    getters: {
        totalBookings: (state) => state.bookings.length,
        totalGuests: (state) => state.guests.length,
        paginatedBookings: (state) => {
            const start = (state.currentPage - 1) * state.itemsPerPage;
            const end = start + state.itemsPerPage;
            return state.bookings.slice(start, end);
        },
        totalPages: (state) => {
            return Math.ceil(state.totalItems / state.itemsPerPage);
        },
        hasNextPage: (state) => {
            return state.currentPage < state.totalPages;
        },
        hasPrevPage: (state) => {
            return state.currentPage > 1;
        },
    },
});
