document.addEventListener('alpine:init', () => {
    Alpine.data("diagram", () => ({
        // activeFilter: new URLSearchParams(window.location.search).get('filter'),
        viewer: null,  // Tambahkan property untuk menyimpan instance viewer
        init() {
            this.loadDiagram();
        },
        async loadDiagram() {
            const bpmnXML = '/diagram/workflow_recruitment.bpmn';
            const { default: BpmnModeler } = await import('bpmn-js/lib/NavigatedViewer');
            // const { default: BpmnModeler } = await import('bpmn-js/lib/Modeler');
            this.viewer = new BpmnModeler({
                container: '#bpmn-container'
            });
            console.log("viewer", this.viewer);
            try {
                const response = await fetch(bpmnXML);
                console.log("response", response);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const xml = await response.text();
                console.log("xml", xml);
                await this.viewer.importXML(xml);
                console.log("viewer2", this.viewer);
                this.viewer.get('canvas').zoom('fit-viewport');
                const eventBus = this.viewer.get('eventBus');
                eventBus.on('element.click', (event) => {
                    const element = event.element;
                    const elementId = element.id;
                    // if (elementId) {
                    //     this.updateFilter(elementId);
                    // }
                    switch (elementId) {
                        case 'Activity_1l23d1f': //filter data pelamar belum di validasi -> Review Data Diri
                            this.updateFilter(elementId);
                            break;
                        case 'Event_0dnse37': // filter data pelamar ditolak -> Notifikasi Tidak Lulus Seleksi
                            this.updateFilter(elementId);
                            break;
                        case 'Activity_1h0pos4': // filter data pelamar yang di lulus tahap seleksi data diri -> input jadwal interview User
                            this.updateFilter(elementId);
                            break;
                        case 'Activity_1frsqol': // filter data pelamar yang di undang wawancara user -> Input Hasil Interview User
                            this.updateFilter(elementId);
                            break;
                        case 'Event_1yf59cf': // filter data pelamar tidak lulus tahap wawancara user -> Notifikasi Tidak Lulus Interview User
                            this.updateFilter(elementId);
                            break;
                        case 'Activity_0y70sst': // filter data pelamar lulus tahap wawancara user -> Input jadwal Interview HR
                            this.updateFilter(elementId);
                            break;
                        case 'Activity_0qi07gz': // filter data pelamar yang diundang wawancara hr -> Input Hasil Interview HR
                            this.updateFilter(elementId);
                            break;
                        case 'Event_00wxbct': // filter data pelamar tidak lulus tahap wawancara hr -> Notifikasi Tidak Lulus Interview HR
                            this.updateFilter(elementId);
                            break;
                        case 'Activity_00fjfo4': // filter data pelamar lulus tahap wawancara hr -> Input Hasil Akhir
                            this.updateFilter(elementId);
                            break;
                        default:
                            this.updateFilter('isNotFilter');
                            break;
                    }
                });
            } catch (err) {
                console.error('Import BPMN gagal:', err);
            }
        },
        updateFilter(filter) {
            // this.activeFilter = filter;
            window.history.pushState({}, '', `${window.location.pathname}?filter=${filter}`); // Update URL
            this.$wire.updateFilter(filter);
        },
        reset() {
            window.history.pushState({}, '', `${window.location.pathname}?filter=${null}`);
            this.$wire.resetFilter()
            this.viewer.get('canvas').zoom('fit-viewport');
        },
        fitToViewport(){
            this.viewer.get('canvas').zoom('fit-viewport');
        },
        zoomIn() {
            const canvas = this.viewer.get('canvas');
            const currentZoom = canvas.zoom();
            canvas.zoom(currentZoom + 0.1);  // Increment zoom level
        },
        zoomOut() {
            const canvas = this.viewer.get('canvas');
            const currentZoom = canvas.zoom();
            canvas.zoom(currentZoom - 0.1);  // Decrement zoom level
        }
    }));
});