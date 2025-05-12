document.addEventListener('DOMContentLoaded', function() {
    // Function to load PDF
    async function loadPDF(filePath) {
        const pdfContainer = document.getElementById('pdf-container');
        pdfContainer.innerHTML = ''; // Clear previous content

        pdfjsLib.getDocument(filePath).promise.then(function(pdf) {
            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                // Fetch each page
                pdf.getPage(pageNum).then(function(page) {
                    const scale = 1.5;
                    const viewport = page.getViewport({ scale: scale });

                    // Prepare canvas using PDF page dimensions
                    const canvas = document.createElement('canvas');
                    canvas.className = 'pdf-page';
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext);

                    // Append canvas to the container
                    pdfContainer.appendChild(canvas);
                });
            }
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
            alert('Error loading PDF. Please check the file path.');
        });
    }

    // Function to toggle the visibility of the PDF container
    function togglePDFContainer(filePath) {
        const pdfContainer = document.getElementById('pdf-container');
        if (pdfContainer.style.display === 'none' || pdfContainer.style.display === '') {
            pdfContainer.style.display = 'block';
            if (!pdfContainer.dataset.loaded) {
                loadPDF(filePath); // Load the PDF when showing the container
                pdfContainer.dataset.loaded = true; // Mark as loaded
            }
        } else {
            pdfContainer.style.display = 'none';
        }
    }

    // Add event listeners to buttons
    document.querySelectorAll('.view-pdf-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const filePath = this.getAttribute('data-file-path');
            console.log('Button clicked! File path:', filePath); // Add this line
            togglePDFContainer(filePath);
        });
    });
});
