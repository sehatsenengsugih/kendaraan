import './bootstrap';
import Alpine from 'alpinejs';

// Global Search Component
window.globalSearch = function() {
    return {
        query: '',
        results: [],
        loading: false,
        showDropdown: false,
        selectedIndex: -1,

        focusSearch() {
            this.$refs.searchInput.focus();
        },

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                this.showDropdown = false;
                return;
            }

            this.loading = true;
            this.showDropdown = true;

            try {
                const response = await fetch(`/search?q=${encodeURIComponent(this.query)}`);
                const data = await response.json();
                this.results = data.results || [];
                this.selectedIndex = -1;
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
            } finally {
                this.loading = false;
            }
        },

        navigateDown() {
            if (this.selectedIndex < this.results.length - 1) {
                this.selectedIndex++;
            }
        },

        navigateUp() {
            if (this.selectedIndex > 0) {
                this.selectedIndex--;
            }
        },

        selectResult() {
            if (this.selectedIndex >= 0 && this.results[this.selectedIndex]) {
                window.location.href = this.results[this.selectedIndex].url;
            }
        }
    };
};

window.Alpine = Alpine;
Alpine.start();
