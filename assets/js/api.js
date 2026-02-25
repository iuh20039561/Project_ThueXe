const API = {
    baseURL: 'controllers/',
    
    cars: {
        getFeatured: async () => {
            const res = await fetch(`${API.baseURL}car_controller.php?action=getFeatured`);
            return await res.json();
        },
        getAll: async () => {
            const res = await fetch(`${API.baseURL}car_controller.php?action=getAll`);
            return await res.json();
        },
        getById: async (id) => {
            const res = await fetch(`${API.baseURL}car_controller.php?action=getById&id=${id}`);
            return await res.json();
        },
        search: async (params) => {
            const query = new URLSearchParams(params).toString();
            const res = await fetch(`${API.baseURL}car_controller.php?action=search&${query}`);
            return await res.json();
        },
        getFilterOptions: async () => {
            const res = await fetch(`${API.baseURL}car_controller.php?action=getFilterOptions`);
            return await res.json();
        }
    },
    
    services: {
        getAll: async () => {
            const res = await fetch(`${API.baseURL}service_controller.php?action=getAll`);
            return await res.json();
        }
    },
    
    bookings: {
        create: async (data) => {
            const res = await fetch(`${API.baseURL}booking_controller.php?action=create`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            return await res.json();
        },
        getById: async (id) => {
            const res = await fetch(`${API.baseURL}booking_controller.php?action=getById&id=${id}`);
            return await res.json();
        }
    }
};