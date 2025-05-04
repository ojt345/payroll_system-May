<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Downloader Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .content {
            margin-bottom: 30px;
        }
        .controls {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #45a049;
        }
        h1, h2 {
            color: #333;
        }
        .sample-content {
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Find the download button
            const downloadPdfBtn = document.getElementById('downloadPdfBtn');
            
            // Add click event listener
            downloadPdfBtn.addEventListener('click', generatePDF);
            
            // Function to generate and download PDF
            function generatePDF() {
                // Show loading indicator
                const loadingIndicator = document.createElement('div');
                loadingIndicator.textContent = 'Generating PDF...';
                loadingIndicator.className = 'pdf-loading-indicator';
                loadingIndicator.style.position = 'fixed';
                loadingIndicator.style.top = '50%';
                loadingIndicator.style.left = '50%';
                loadingIndicator.style.transform = 'translate(-50%, -50%)';
                loadingIndicator.style.padding = '20px';
                loadingIndicator.style.background = 'rgba(0,0,0,0.7)';
                loadingIndicator.style.color = 'white';
                loadingIndicator.style.borderRadius = '5px';
                loadingIndicator.style.zIndex = '9999';
                document.body.appendChild(loadingIndicator);
                
                // Load required libraries dynamically
                loadScript('https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js')
                    .then(() => {
                        createPDF();
                    })
                    .catch(error => {
                        console.error('Error loading libraries:', error);
                        alert('Failed to load PDF generation libraries. Check console for details.');
                        document.body.removeChild(loadingIndicator);
                    });
            }
            
            // Helper function to load scripts
            function loadScript(src) {
                return new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    script.src = src;
                    script.onload = resolve;
                    script.onerror = reject;
                    document.head.appendChild(script);
                });
            }
            
            function createPDF() {
                // Get the content area - select the specific element that contains your content
                // You may need to adjust this selector to match your actual page structure
                const contentToCapture = document.querySelector('.container') || document.body;
                
                // Clone the content to avoid modifying the original page
                const contentClone = contentToCapture.cloneNode(true);
                
                // Remove elements that shouldn't be in the PDF
                const controlsToRemove = contentClone.querySelectorAll('.controls, .pdf-loading-indicator');
                controlsToRemove.forEach(element => {
                    if (element && element.parentNode) {
                        element.parentNode.removeChild(element);
                    }
                });
                
                // Configure html2pdf options
                const options = {
                    margin: 10,
                    filename: 'webpage-content.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { 
                        scale: 2, // Better resolution
                        useCORS: true,
                        logging: true,
                        letterRendering: true
                    },
                    jsPDF: { 
                        unit: 'mm', 
                        format: [215.9, 330.2], // 8.5 x 13 inches in mm
                        orientation: 'portrait' 
                    }
                };
                
                // Generate PDF
                html2pdf().from(contentClone).set(options).save()
                    .then(() => {
                        // Remove loading indicator when done
                        const loadingIndicator = document.querySelector('.pdf-loading-indicator');
                        if (loadingIndicator && loadingIndicator.parentNode) {
                            loadingIndicator.parentNode.removeChild(loadingIndicator);
                        }
                    })
                    .catch(error => {
                        console.error('Error generating PDF:', error);
                        alert('Failed to generate PDF. Check console for details.');
                        const loadingIndicator = document.querySelector('.pdf-loading-indicator');
                        if (loadingIndicator && loadingIndicator.parentNode) {
                            loadingIndicator.parentNode.removeChild(loadingIndicator);
                        }
                    });
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="controls">
            <button id="downloadPdfBtn" class="btn">Download as PDF</button>
        </div>
        
        <div class="content">
            <h1>PDF Download Example</h1>
            <p>This page demonstrates how to download the contents of a webpage as a PDF with custom page size (8.5 x 13 inches portrait).</p>
            
            <h2>How it works</h2>
            <p>When you click the "Download as PDF" button above, the script will:</p>
            <ol>
                <li>Load required libraries (html2pdf.js)</li>
                <li>Capture the content of this page</li>
                <li>Convert it to a PDF with 8.5 x 13 inch paper size</li>
                <li>Handle long content by splitting it across multiple pages if needed</li>
                <li>Download the PDF file to your device</li>
            </ol>
            
            <div class="sample-content">
                <h2>Sample Content Section</h2>
                <p>This is some sample content that will be included in the PDF. The PDF generation process captures everything in the page except for the download button at the top.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Phasellus venenatis, nisi eget ultricies dignissim, nisl nisl aliquam nisl, eget aliquam nisl nisl sit amet nisl. Donec euismod, nisl eget aliquam nisi.</p>
                <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer quis sapien a magna ullamcorper hendrerit. Duis aliquam, magna vitae aliquam ullamcorper, felis felis hendrerit neque, eget aliquam neque nisi eget nisl.</p>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    // Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Find the download button
    const downloadPdfBtn = document.getElementById('downloadPdfBtn');
    
    // Add click event listener
    downloadPdfBtn.addEventListener('click', generatePDF);
    
    // Function to generate and download PDF
    function generatePDF() {
        // Show loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.textContent = 'Generating PDF...';
        loadingIndicator.className = 'pdf-loading-indicator';
        loadingIndicator.style.position = 'fixed';
        loadingIndicator.style.top = '50%';
        loadingIndicator.style.left = '50%';
        loadingIndicator.style.transform = 'translate(-50%, -50%)';
        loadingIndicator.style.padding = '20px';
        loadingIndicator.style.background = 'rgba(0,0,0,0.7)';
        loadingIndicator.style.color = 'white';
        loadingIndicator.style.borderRadius = '5px';
        loadingIndicator.style.zIndex = '9999';
        document.body.appendChild(loadingIndicator);
        
        // Load required libraries dynamically
        loadScript('https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js')
            .then(() => {
                createPDF();
            })
            .catch(error => {
                console.error('Error loading libraries:', error);
                alert('Failed to load PDF generation libraries. Check console for details.');
                document.body.removeChild(loadingIndicator);
            });
    }
    
    // Helper function to load scripts
    function loadScript(src) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = src;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }
    
    function createPDF() {
        // Get the content area - select the specific element that contains your content
        // You may need to adjust this selector to match your actual page structure
        const contentToCapture = document.querySelector('.container') || document.body;
        
        // Clone the content to avoid modifying the original page
        const contentClone = contentToCapture.cloneNode(true);
        
        // Remove elements that shouldn't be in the PDF
        const controlsToRemove = contentClone.querySelectorAll('.controls, .pdf-loading-indicator');
        controlsToRemove.forEach(element => {
            if (element && element.parentNode) {
                element.parentNode.removeChild(element);
            }
        });
        
        // Configure html2pdf options
        const options = {
            margin: 10,
            filename: 'webpage-content.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: 2, // Better resolution
                useCORS: true,
                logging: true,
                letterRendering: true
            },
            jsPDF: { 
                unit: 'mm', 
                format: [215.9, 330.2], // 8.5 x 13 inches in mm
                orientation: 'portrait' 
            }
        };
        
        // Generate PDF
        html2pdf().from(contentClone).set(options).save()
            .then(() => {
                // Remove loading indicator when done
                const loadingIndicator = document.querySelector('.pdf-loading-indicator');
                if (loadingIndicator && loadingIndicator.parentNode) {
                    loadingIndicator.parentNode.removeChild(loadingIndicator);
                }
            })
            .catch(error => {
                console.error('Error generating PDF:', error);
                alert('Failed to generate PDF. Check console for details.');
                const loadingIndicator = document.querySelector('.pdf-loading-indicator');
                if (loadingIndicator && loadingIndicator.parentNode) {
                    loadingIndicator.parentNode.removeChild(loadingIndicator);
                }
            });
    }
});
</script>