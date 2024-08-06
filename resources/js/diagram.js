document.addEventListener('alpine:init', () => {
    Alpine.data("diagram", () => ({
        // activeFilter: new URLSearchParams(window.location.search).get('filter'),
        viewer: null,  // Tambahkan property untuk menyimpan instance viewer
        async init() {
            this.loadDiagram();
        },
        async loadDiagram() {
            const bpmnXML = '/diagram/workflow_recruitment.bpmn';
            const { default: BpmnModeler } = await import('bpmn-js');
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
                    if (elementId) {
                        this.updateFilter(elementId);
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
        reset(){
            window.history.pushState({}, '', `${window.location.pathname}?filter=${null}`);
            this.$wire.resetFilter()
        },
        zoomIn() {
            if (this.viewer) {
                this.viewer.get('canvas').zoom(1.2);  // Zoom in by 20%
            }
        },
        zoomOut() {
            if (this.viewer) {
                this.viewer.get('canvas').zoom(0.8);  // Zoom out by 20%
            }
        },
    }));
});

// document.addEventListener('alpine:init', () => {
//     Alpine.data("diagram", () => ({
//         activeFilter: new URLSearchParams(window.location.search).get('filter'),
//         async init() {
//             this.loadDiagram();
//         },
//         async loadDiagram() {
//             const bpmnXML = '/diagram/workflow_recruitment.bpmn';
//             const { default: BpmnModeler } = await import('bpmn-js');
//             const viewer = new BpmnModeler({
//                 container: '#bpmn-container'
//             });
//             console.log("viewer", viewer)
//             try {
//                 const response = await fetch(bpmnXML);
//                 console.log("response", response)
//                 if (!response.ok) {
//                     throw new Error('Network response was not ok');
//                 }
//                 const xml = await response.text();
//                 console.log("xml", xml)
//                 await viewer.importXML(xml);
//                 console.log("viewer2", viewer)
//                 viewer.get('canvas').zoom('fit-viewport');
//                 const eventBus = viewer.get('eventBus');
//                 eventBus.on('element.click', (event) => {
//                     const element = event.element;
//                     const elementId = element.id;
//                     if (elementId) {
//                         this.updateFilter(elementId);
//                     }
//                 });
//             } catch (err) {
//                 console.error('Import BPMN gagal:', err);
//             }
//         },
//         updateFilter(filter) {
//             this.activeFilter = filter;
//             window.history.pushState({}, '', `${window.location.pathname}?filter=${filter}`); // Update URL
//             this.$wire.updateFilter(filter);
//         },
//         zoomIn() {
//             alert("hellwo")
//             if (this.viewer) {
//                 this.viewer.get('canvas').zoom(1.2);  // Zoom in by 20%
//             }
//         },
//         zoomOut() {
//             if (this.viewer) {
//                 this.viewer.get('canvas').zoom(0.8);  // Zoom out by 20%
//             }
//         },
//     }));
// });