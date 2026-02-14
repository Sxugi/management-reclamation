export class QrCodeDownloader {
    constructor(plotId, plotName) {
        this.plotId = plotId;
        this.plotName = plotName;
        this.downloading = false;
    }

    async download() {
        if (this.downloading) return;
        
        this.downloading = true;
        
        try {
            const svg = document.querySelector(`#qrcode-${this.plotId} svg`);
            
            if (!svg) {
                throw new Error('QR Code not found');
            }

            const svgData = new XMLSerializer().serializeToString(svg);
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Set canvas size
            canvas.width = 600;
            canvas.height = 600;
            
            const img = new Image();
            
            // Wait for image to load
            await new Promise((resolve, reject) => {
                img.onload = resolve;
                img.onerror = () => reject(new Error('Failed to load QR Code image'));
                img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
            });
            
            // Draw white background
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Draw QR code centered with padding
            ctx.drawImage(img, 50, 50, 500, 500);
            
            // Convert to PNG and download
            await new Promise((resolve, reject) => {
                canvas.toBlob((blob) => {
                    if (!blob) {
                        reject(new Error('Failed to generate PNG'));
                        return;
                    }
                    
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    const timestamp = new Date().getTime();
                    
                    link.href = url;
                    link.download = `qrcode-${this.plotName}-${timestamp}.png`;
                    
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    URL.revokeObjectURL(url);
                    resolve();
                }, 'image/png', 1.0);
            });
            
            return true;
            
        } catch (error) {
            console.error('QR Code Download Error:', error);
            alert('Gagal download QR Code. Silakan coba lagi.');
            throw error;
            
        } finally {
            this.downloading = false;
        }
    }

    isDownloading() {
        return this.downloading;
    }
}

// Global function untuk dipanggil dari Alpine.js
window.downloadQrCode = function(plotId, plotName) {
    const downloader = new QrCodeDownloader(plotId, plotName);
    return downloader.download();
};