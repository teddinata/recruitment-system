document.addEventListener('alpine:init', () => {
    Alpine.data("diagram", () => ({
        activeFilter: new URLSearchParams(window.location.search).get('filter'),
        async init() {
            const bpmnXML = '/diagram/workflow_recruitment.bpmn';
            const viewer = new BpmnJS({
                container: '#bpmn-container'
            });

            try {
                const response = await fetch(bpmnXML);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const xml = await response.text();
                await viewer.importXML(xml);
                viewer.get('canvas').zoom('fit-viewport');

                const eventBus = viewer.get('eventBus');

                eventBus.on('element.click', (event) => {
                    const element = event.element;
                    const elementId = element.id;
                    if (elementId) {
                        this.updateFilter(elementId);
                    }
                });
            } catch (err) {
                console.error('Impor BPMN gagal:', err);
            }
        },
        updateFilter(filter) {
            this.activeFilter = filter;
            const url = new URL(window.location.href);
            url.searchParams.set('filter', filter);
            history.pushState({}, '', url);

            // Mengirim AJAX request untuk memperbarui tabel
            this.updateTable(filter);
        },
        updateTable(filter) {
            $.ajax({
                url: window.location.pathname,
                type: 'GET',
                data: { filter: filter },
                success: (response) => {
                    // Ganti konten tabel dengan respons baru
                    $('#table-container').html(response);
                },
                error: (xhr, status, error) => {
                    console.error('AJAX request gagal:', status, error);
                }
            });
        }
    }));
});