/* assets/js/script.js */

document.addEventListener('DOMContentLoaded', () => {
    // ==========================================
    // 1. NIGHT & LIGHT THEME SWITCHER
    // ==========================================
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = themeToggle ? themeToggle.querySelector('i') : null;
    
    // Check saved theme or default to dark
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            playAudio('click');
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    function updateThemeIcon(theme) {
        if (!themeIcon) return;
        if (theme === 'dark') {
            themeIcon.className = 'fas fa-moon';
        } else {
            themeIcon.className = 'fas fa-sun';
        }
    }


    // ==========================================
    // 2. AUDIO SYNTHESIZER (WEB AUDIO API)
    // ==========================================
    // This allows us to have audio effects without loading external files
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    let isSoundMuted = localStorage.getItem('muted') === 'true';

    const muteToggle = document.getElementById('muteToggle');
    const muteIcon = muteToggle ? muteToggle.querySelector('i') : null;
    updateMuteIcon();

    if (muteToggle) {
        muteToggle.addEventListener('click', () => {
            isSoundMuted = !isSoundMuted;
            localStorage.setItem('muted', isSoundMuted);
            updateMuteIcon();
            if (!isSoundMuted) {
                playAudio('click');
            }
        });
    }

    function updateMuteIcon() {
        if (!muteIcon) return;
        if (isSoundMuted) {
            muteIcon.className = 'fas fa-volume-mute';
        } else {
            muteIcon.className = 'fas fa-volume-up';
        }
    }

    function playAudio(type) {
        if (isSoundMuted) return;
        
        // Resume AudioContext if suspended (browser security)
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }

        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);

        const now = audioCtx.currentTime;

        if (type === 'click') {
            // Short gaming click sound
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(440, now); // A4
            osc.frequency.exponentialRampToValueAtTime(880, now + 0.05);
            gain.gain.setValueAtTime(0.1, now);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.08);
            osc.start(now);
            osc.stop(now + 0.08);
        } else if (type === 'success') {
            // Power up retro success sound
            osc.type = 'sine';
            osc.frequency.setValueAtTime(300, now);
            osc.frequency.exponentialRampToValueAtTime(600, now + 0.1);
            osc.frequency.exponentialRampToValueAtTime(1200, now + 0.25);
            gain.gain.setValueAtTime(0.15, now);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.3);
            osc.start(now);
            osc.stop(now + 0.3);
        } else if (type === 'clear') {
            // Swoosh clear sound
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(400, now);
            osc.frequency.exponentialRampToValueAtTime(100, now + 0.15);
            gain.gain.setValueAtTime(0.08, now);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.15);
            osc.start(now);
            osc.stop(now + 0.15);
        } else if (type === 'draw') {
            // Drawing scribble sound (extremely low volume white noise/hum)
            osc.type = 'sine';
            osc.frequency.setValueAtTime(180, now);
            gain.gain.setValueAtTime(0.02, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.05);
            osc.start(now);
            osc.stop(now + 0.05);
        }
    }

    // Attach click sound to all buttons
    document.querySelectorAll('.btn, .btn-icon, .nav-links a').forEach(btn => {
        btn.addEventListener('click', () => {
            // Avoid double-triggering for mute/theme switch
            if (btn.id !== 'themeToggle' && btn.id !== 'muteToggle') {
                playAudio('click');
            }
        });
    });


    // ==========================================
    // 3. CANVAS DIGITAL SIGNATURE
    // ==========================================
    const canvas = document.getElementById('signatureCanvas');
    const signatureInput = document.getElementById('signatureData');
    const clearBtn = document.getElementById('clearSignature');

    if (canvas) {
        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;

        // Set line styling
        ctx.strokeStyle = '#1e272e'; // Dark ink by default
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        // Fix canvas resolution (retina display support)
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;

        // Re-scale on resize
        window.addEventListener('resize', () => {
            const tempImg = canvas.toDataURL();
            const r = canvas.getBoundingClientRect();
            canvas.width = r.width;
            canvas.height = r.height;
            // Redraw signature on resize
            const img = new Image();
            img.onload = () => ctx.drawImage(img, 0, 0);
            img.src = tempImg;
        });

        // Mouse Events
        canvas.addEventListener('mousedown', (e) => {
            isDrawing = true;
            [lastX, lastY] = getMousePos(e);
            playAudio('draw');
        });

        canvas.addEventListener('mousemove', (e) => {
            if (!isDrawing) return;
            draw(e);
        });

        canvas.addEventListener('mouseup', () => {
            isDrawing = false;
            saveSignature();
        });

        canvas.addEventListener('mouseout', () => {
            isDrawing = false;
        });

        // Touch Events (Mobile)
        canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            isDrawing = true;
            const touch = e.touches[0];
            [lastX, lastY] = getTouchPos(touch);
            playAudio('draw');
        });

        canvas.addEventListener('touchmove', (e) => {
            e.preventDefault();
            if (!isDrawing) return;
            const touch = e.touches[0];
            drawTouch(touch);
        });

        canvas.addEventListener('touchend', () => {
            isDrawing = false;
            saveSignature();
        });

        function getMousePos(e) {
            const r = canvas.getBoundingClientRect();
            return [
                e.clientX - r.left,
                e.clientY - r.top
            ];
        }

        function getTouchPos(touch) {
            const r = canvas.getBoundingClientRect();
            return [
                touch.clientX - r.left,
                touch.clientY - r.top
            ];
        }

        function draw(e) {
            const [x, y] = getMousePos(e);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);
            ctx.stroke();
            [lastX, lastY] = [x, y];
            
            // Subtle drawing audio trigger
            if (Math.random() < 0.2) playAudio('draw');
        }

        function drawTouch(touch) {
            const [x, y] = getTouchPos(touch);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);
            ctx.stroke();
            [lastX, lastY] = [x, y];
            
            if (Math.random() < 0.2) playAudio('draw');
        }

        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signatureInput.value = '';
            playAudio('clear');
        }

        function saveSignature() {
            // Check if the canvas is blank before saving
            if (isCanvasBlank()) {
                signatureInput.value = '';
            } else {
                signatureInput.value = canvas.toDataURL('image/png');
            }
        }

        function isCanvasBlank() {
            const blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            return canvas.toDataURL() === blank.toDataURL();
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', clearCanvas);
        }

        // Form Submit Validation
        const orderForm = document.getElementById('orderForm');
        if (orderForm) {
            orderForm.addEventListener('submit', (e) => {
                if (signatureInput.value === '') {
                    e.preventDefault();
                    alert('Harap buat Tanda Tangan Digital terlebih dahulu!');
                    playAudio('clear');
                } else {
                    playAudio('success');
                }
            });
        }
    }


    // ==========================================
    // 4. MODALS MANAGEMENT
    // ==========================================
    const modals = document.querySelectorAll('.modal');
    
    // Function to open modal
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
            playAudio('click');
        }
    };

    // Function to close modal
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
            playAudio('click');
        }
    };

    // Close on overlay click or close button
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });

        const closeBtn = modal.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                closeModal(modal.id);
            });
        }
    });


    // ==========================================
    // 5. DATATABLE LOGIC (CLIENT SIDE SEARCH & SORT & CONVERT)
    // ==========================================
    const searchInput = document.getElementById('datatableSearch');
    const table = document.getElementById('datatableOrders');

    if (table) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const headers = Array.from(table.querySelectorAll('thead th'));

        // Instant search
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }

        // Sorting
        headers.forEach((header, index) => {
            // Exclude action / files columns
            if (header.innerText.toLowerCase() === 'aksi' || header.innerText.toLowerCase() === 'screenshot') return;

            header.addEventListener('click', () => {
                const isAscending = header.classList.contains('th-asc');
                
                // Reset other headers
                headers.forEach(h => h.className = '');

                // Set new state
                header.classList.add(isAscending ? 'th-desc' : 'th-asc');

                rows.sort((rowA, rowB) => {
                    const valA = getCellValue(rowA, index);
                    const valB = getCellValue(rowB, index);

                    if (!isNaN(valA) && !isNaN(valB)) {
                        return isAscending ? valB - valA : valA - valB;
                    }

                    return isAscending 
                        ? valB.localeCompare(valA) 
                        : valA.localeCompare(valB);
                });

                // Re-append rows in new order
                rows.forEach(row => tbody.appendChild(row));
                playAudio('click');
            });
        });

        function getCellValue(row, index) {
            const cell = row.children[index];
            // If it is Price, remove non-numeric chars
            if (cell.classList.contains('cell-price')) {
                return parseFloat(cell.getAttribute('data-price') || cell.innerText.replace(/[^0-9]/g, ''));
            }
            // If it is Date
            if (cell.classList.contains('cell-date')) {
                return new Date(cell.getAttribute('data-date') || cell.innerText);
            }
            return cell.innerText.trim();
        }

        // Auto convert formatting on page load
        convertDataOnTable();
    }

    function convertDataOnTable() {
        // Price conversion to IDR format
        document.querySelectorAll('.cell-price').forEach(cell => {
            const price = parseFloat(cell.getAttribute('data-price'));
            if (!isNaN(price)) {
                cell.innerText = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(price);
            }
        });

        // Date conversion to local format
        document.querySelectorAll('.cell-date').forEach(cell => {
            const rawDate = cell.getAttribute('data-date');
            if (rawDate) {
                const date = new Date(rawDate);
                cell.innerText = date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) + ' WIB';
            }
        });
    }
});


