/**
 * encrypted-mode.js
 * Script Honeypot / Mode Enkripsi Teks MoneyTrack
 * Mengenkripsi SELURUH teks antarmuka, form, label, input, tombol, modal,
 * TERMASUK teks tombol Logout saat salah memasukkan private key.
 */

(function () {
    'use strict';

    const CIPHER_CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

    // Fungsi enkripsi kata deterministik menyerupai AES/Base64 ciphertext
    function cipherWord(word) {
        if (!word) return word;

        // Pertahankan karakter simbol/tanda baca murni
        if (/^[^a-zA-Z0-9]+$/.test(word)) return word;

        // Hash deterministik sederhana berdasarkan isi kata
        let hash = 0;
        for (let i = 0; i < word.length; i++) {
            hash = ((hash << 5) - hash) + word.charCodeAt(i);
            hash |= 0;
        }

        let seed = Math.abs(hash) + 1337;
        function nextRand() {
            seed = (seed * 9301 + 49297) % 233280;
            return seed / 233280;
        }

        const len = word.length;
        // Jaga panjang kata agar layout tabel & tombol tetap rapi
        let targetLen = len <= 3 ? len : (len + (nextRand() > 0.6 ? 1 : 0));

        let result = '';
        for (let i = 0; i < targetLen; i++) {
            const idx = Math.floor(nextRand() * 62);
            result += CIPHER_CHARS[idx];
        }

        // Tambahkan padding Base64 '=' sesekali untuk kata panjang
        if (targetLen >= 5 && nextRand() > 0.45) {
            result = result.substring(0, targetLen - 1) + '=';
        }

        return result;
    }

    // Fungsi enkripsi kalimat/string (mempertahankan spasi dan baris baru)
    function cipherText(text) {
        if (!text || typeof text !== 'string') return text;

        return text.replace(/([a-zA-Z0-9\-_]+)/g, function (match) {
            return cipherWord(match);
        });
    }

    // Pengecekan apakah sebuah elemen adalah elemen internal yang tidak boleh dirusak
    function isExcluded(el) {
        if (!el || el.nodeType !== Node.ELEMENT_NODE) return false;

        const tagName = el.tagName.toLowerCase();
        if (tagName === 'script' || tagName === 'style' || tagName === 'noscript') {
            return true;
        }

        // Jangan ubah class ikon FontAwesome
        if (tagName === 'i' && (el.classList.contains('fas') || el.classList.contains('fa') || el.classList.contains('far') || el.classList.contains('fab'))) {
            return true;
        }

        return false;
    }

    // Enkripsi satu node teks
    function encryptTextNode(node) {
        if (!node || node.nodeType !== Node.TEXT_NODE) return;

        const parent = node.parentElement;
        if (!parent || isExcluded(parent)) return;

        const raw = node.nodeValue;
        if (!raw || !raw.trim()) return;

        const encrypted = cipherText(raw);
        if (node.nodeValue !== encrypted) {
            node.nodeValue = encrypted;
        }
    }

    // Enkripsi elemen form (input, textarea, select, button)
    function encryptFormElement(el) {
        if (!el || el.nodeType !== Node.ELEMENT_NODE) return;
        if (isExcluded(el)) return;

        const tag = el.tagName.toLowerCase();

        // 1. Input teks, number, search, date, dll
        if (tag === 'input') {
            if (el.type === 'hidden') return;

            // Ubah tipe number dan date menjadi text agar bisa menampilkan ciphertext tanpa error HTML5
            if (el.type === 'number' || el.type === 'date') {
                try {
                    el.type = 'text';
                } catch (e) {}
            }

            if (el.placeholder && !el.dataset.encryptedPlaceholder) {
                el.placeholder = cipherText(el.placeholder);
                el.dataset.encryptedPlaceholder = 'true';
            }

            if (el.type !== 'password' && el.value && !el.dataset.encryptedValue) {
                el.value = cipherText(el.value);
                el.dataset.encryptedValue = 'true';
            }

            // Realtime scrambler ketika pengguna mengetik di form apapun
            if (!el.dataset.listenerAttached) {
                el.dataset.listenerAttached = 'true';
                el.addEventListener('input', function (e) {
                    if (e.target.type !== 'password') {
                        const original = e.target.value;
                        const scrambled = cipherText(original);
                        if (original !== scrambled) {
                            const start = e.target.selectionStart;
                            const end = e.target.selectionEnd;
                            e.target.value = scrambled;
                            e.target.setSelectionRange(start, end);
                        }
                    }
                });
            }
        }

        // 2. Textarea
        if (tag === 'textarea') {
            if (el.placeholder && !el.dataset.encryptedPlaceholder) {
                el.placeholder = cipherText(el.placeholder);
                el.dataset.encryptedPlaceholder = 'true';
            }

            if (el.value && !el.dataset.encryptedValue) {
                el.value = cipherText(el.value);
                el.dataset.encryptedValue = 'true';
            }

            if (!el.dataset.listenerAttached) {
                el.dataset.listenerAttached = 'true';
                el.addEventListener('input', function (e) {
                    const original = e.target.value;
                    const scrambled = cipherText(original);
                    if (original !== scrambled) {
                        const start = e.target.selectionStart;
                        const end = e.target.selectionEnd;
                        e.target.value = scrambled;
                        e.target.setSelectionRange(start, end);
                    }
                });
            }
        }

        // 3. Select Options
        if (tag === 'select') {
            const options = el.querySelectorAll('option');
            options.forEach(opt => {
                if (opt.textContent && !opt.dataset.encryptedText) {
                    opt.textContent = cipherText(opt.textContent);
                    opt.dataset.encryptedText = 'true';
                }
            });
        }

        // 4. Button attributes (title / value)
        if (tag === 'button') {
            if (el.title) el.title = cipherText(el.title);
            if (el.value) el.value = cipherText(el.value);
        }
    }

    // Traversal pohon DOM untuk mengenkripsi semua node teks dan elemen form
    function encryptTree(root) {
        if (!root) return;

        // Enkripsi elemen root jika berupa form element
        encryptFormElement(root);

        // Cari seluruh elemen form
        const formElements = root.querySelectorAll('input, textarea, select, button');
        formElements.forEach(encryptFormElement);

        // Gunakan TreeWalker untuk menemukan semua node teks dengan cepat dan aman
        const walker = document.createTreeWalker(
            root,
            NodeFilter.SHOW_TEXT,
            {
                acceptNode: function (node) {
                    const parent = node.parentElement;
                    if (!parent || isExcluded(parent)) {
                        return NodeFilter.FILTER_REJECT;
                    }
                    if (!node.nodeValue || !node.nodeValue.trim()) {
                        return NodeFilter.FILTER_REJECT;
                    }
                    return NodeFilter.FILTER_ACCEPT;
                }
            }
        );

        const nodesToEncrypt = [];
        let currentNode = walker.nextNode();
        while (currentNode) {
            nodesToEncrypt.push(currentNode);
            currentNode = walker.nextNode();
        }

        for (let i = 0; i < nodesToEncrypt.length; i++) {
            encryptTextNode(nodesToEncrypt[i]);
        }
    }

    // Jalankan enkripsi ke seluruh dokumen
    function runEncryption() {
        if (document.body) {
            encryptTree(document.body);
        }

        // Enkripsi judul halaman
        if (document.title && !document.title.startsWith('[')) {
            document.title = '[' + cipherText(document.title) + ']';
        }

        // Hapus cloak anti-flicker segera setelah enkripsi selesai
        document.documentElement.classList.remove('encrypt-loading');
    }

    // Eksekusi segera & saat DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runEncryption);
    } else {
        runEncryption();
    }

    // Pantau perubahan dinamis (Modal Bootstrap, penambahan elemen baru, dll)
    const observer = new MutationObserver((mutations) => {
        observer.disconnect(); // Hindari loop rekursif
        for (const mutation of mutations) {
            for (const addedNode of mutation.addedNodes) {
                if (addedNode.nodeType === Node.ELEMENT_NODE && !isExcluded(addedNode)) {
                    encryptTree(addedNode);
                } else if (addedNode.nodeType === Node.TEXT_NODE) {
                    encryptTextNode(addedNode);
                }
            }
        }
        observer.observe(document.body, { childList: true, subtree: true });
    });

    if (document.body) {
        observer.observe(document.body, { childList: true, subtree: true });
    } else {
        document.addEventListener('DOMContentLoaded', () => {
            observer.observe(document.body, { childList: true, subtree: true });
        });
    }

    // Event listener tambahan saat modal Bootstrap muncul
    document.addEventListener('show.bs.modal', function (event) {
        if (event.target) {
            encryptTree(event.target);
        }
    });

    document.addEventListener('shown.bs.modal', function (event) {
        if (event.target) {
            encryptTree(event.target);
        }
    });

})();
