

    let currentPrice = 0;

    let finalTotal = 0;

    let currentMode = "ticket";

    let currentItemName = "";

    let currentQty = 1;

    let selectedPaymentMethod = null;

    const SHIPPING_COST = 15000;



    /* =========================

       POPUP TIKET

    ========================= */



    // Batas sederhana di sisi browser: 5 tiket kumulatif per email.
    // Untuk keamanan transaksi sungguhan, batas yang sama wajib divalidasi di server/database.
    const MAX_TICKETS_PER_EMAIL = 5;
    const TICKET_LIMIT_STORAGE_KEY = "slrTicketCountsByEmail";
    let checkoutTicketEmail = "";
    let checkoutTicketQty = 1;

    function normalizeTicketEmail(email){
        return String(email || "").trim().toLowerCase();
    }

    function getTicketCounts(){
        try { return JSON.parse(localStorage.getItem(TICKET_LIMIT_STORAGE_KEY) || "{}"); }
        catch(error){ return {}; }
    }

    function getPurchasedTicketCount(email){
        const key = normalizeTicketEmail(email);
        return key ? (Number(getTicketCounts()[key]) || 0) : 0;
    }

    function getRemainingTickets(email){
        return Math.max(0, MAX_TICKETS_PER_EMAIL - getPurchasedTicketCount(email));
    }

    function updateTicketLimit(){
        const emailInput = document.getElementById("ticketEmail");
        const amountInput = document.getElementById("ticketAmount");
        const info = document.getElementById("ticketLimitInfo");
        if(!emailInput || !amountInput || !info) return;

        const email = normalizeTicketEmail(emailInput.value);
        const remaining = email ? getRemainingTickets(email) : MAX_TICKETS_PER_EMAIL;
        amountInput.max = String(Math.max(1, remaining));
        info.textContent = email
          ? `Email ini sudah membeli ${MAX_TICKETS_PER_EMAIL - remaining} tiket. Sisa kuota: ${remaining} tiket.`
          : `Maksimal ${MAX_TICKETS_PER_EMAIL} tiket untuk setiap alamat email.`;

        if(remaining === 0){
            amountInput.value = 1;
            amountInput.disabled = true;
            info.textContent = "Batas 5 tiket untuk email ini sudah tercapai.";
        } else {
            amountInput.disabled = false;
            if((parseInt(amountInput.value, 10) || 1) > remaining){
                amountInput.value = remaining;
            }
        }
        updateTotal();
    }

    function savePurchasedTickets(email, quantity){
        const key = normalizeTicketEmail(email);
        if(!key) return false;
        const counts = getTicketCounts();
        const current = Number(counts[key]) || 0;
        if(current + quantity > MAX_TICKETS_PER_EMAIL) return false;
        counts[key] = current + quantity;
        localStorage.setItem(TICKET_LIMIT_STORAGE_KEY, JSON.stringify(counts));
        return true;
    }

    function openPopup(city, price){



        currentPrice = price;



        document.getElementById("popupTitle").innerText =

            "BELI TIKET - " + city;



        document.getElementById("ticketName").value = "";



        document.getElementById("ticketEmail").value = "";



        document.getElementById("ticketAmount").value = 1;
        document.getElementById("ticketAmount").disabled = false;

        updateTicketLimit();



        document

            .getElementById("ticketPopup")

            .classList.add("active");



    }



    function closePopup(){



        document

            .getElementById("ticketPopup")

            .classList.remove("active");



    }



    function updateTotal(){



        const amount =

            parseInt(document.getElementById("ticketAmount").value) || 1;



        finalTotal =

            (amount * currentPrice) + 5000;



        document.getElementById("ticketPrice").innerText =

            "Rp" + currentPrice.toLocaleString("id-ID");



        document.getElementById("totalPrice").innerText =

            "Rp" + finalTotal.toLocaleString("id-ID");



    }



    /* =========================

       POPUP MERCH

    ========================= */



    function openMerchPopup(item,price){



        currentPrice = price;

        currentItemName = item;



        document.getElementById("merchPopupTitle").innerText =

            "BELI - " + item;



        document.getElementById("merchName").value = "";



        document.getElementById("merchPhone").value = "";



        document.getElementById("merchAddress").value = "";



        document.getElementById("merchNote").value = "";



        document.getElementById("merchAmount").value = 1;



        document.getElementById("merchSize").value = "";



        const sizeField = document.getElementById("merchSizeField");



        if(item.toUpperCase().includes("KAOS")){



            sizeField.style.display = "block";



        } else {



            sizeField.style.display = "none";



        }



        updateMerchTotal();



        document

            .getElementById("merchPopup")

            .classList.add("active");



    }



    function closeMerchPopup(){



        document

            .getElementById("merchPopup")

            .classList.remove("active");



    }



    function updateMerchTotal(){



        const amount =

            parseInt(document.getElementById("merchAmount").value) || 1;



        currentQty = amount;



        finalTotal =

            (amount * currentPrice) + 5000 + SHIPPING_COST;



        document.getElementById("merchPrice").innerText =

            "Rp" + currentPrice.toLocaleString("id-ID");



        document.getElementById("merchTotalPrice").innerText =

            "Rp" + finalTotal.toLocaleString("id-ID");



    }



    /* =========================

       PEMBAYARAN

    ========================= */



    function openPaymentPopup(type){



        currentMode = type;



        let valid = true;



        if(type === "ticket"){



            const name = document.getElementById("ticketName");

            const email = document.getElementById("ticketEmail");



            name.classList.remove("field-error");

            email.classList.remove("field-error");



            if(name.value.trim() === ""){

                name.classList.add("field-error");

                valid = false;

            }



            if(

                email.value.trim() === "" ||

                !email.value.includes("@")

            ){

                email.classList.add("field-error");

                valid = false;

            }



if(valid){
                const emailKey = normalizeTicketEmail(email.value);
                const qty = parseInt(document.getElementById("ticketAmount").value, 10) || 1;
                const remaining = getRemainingTickets(emailKey);
                if(qty < 1 || qty > remaining || qty > MAX_TICKETS_PER_EMAIL){
                    alert(`Email ini hanya dapat membeli maksimal ${remaining} tiket lagi (batas 5 tiket per email).`);
                    updateTicketLimit();
                    valid = false;
                } else {
                    checkoutTicketEmail = emailKey;
                    checkoutTicketQty = qty;
                }
            }

            if(!valid){
                alert("Mohon isi Nama dan Email dengan benar, serta periksa batas pembelian tiket.");
                return;
            }



            document.getElementById("successTicketName").innerText =

                name.value;



            document.getElementById("successTicketEmail").innerText =

                email.value;



            closePopup();



        }



        if(type === "merch"){



            const name = document.getElementById("merchName");

            const phone = document.getElementById("merchPhone");

            const address = document.getElementById("merchAddress");

            const sizeField = document.getElementById("merchSizeField");

            const size = document.getElementById("merchSize");



            name.classList.remove("field-error");

            phone.classList.remove("field-error");

            address.classList.remove("field-error");

            size.classList.remove("field-error");



            if(name.value.trim() === ""){

                name.classList.add("field-error");

                valid = false;

            }



            if(

                phone.value.trim() === "" ||

                phone.value.trim().length < 9

            ){

                phone.classList.add("field-error");

                valid = false;

            }



            if(address.value.trim() === ""){

                address.classList.add("field-error");

                valid = false;

            }



            if(

                sizeField.style.display !== "none" &&

                size.value === ""

            ){

                size.classList.add("field-error");

                valid = false;

            }



            if(!valid){

                alert("Mohon lengkapi semua data pengiriman dengan benar.");

                return;

            }



            document.getElementById("successMerchName").innerText =

                name.value;



            document.getElementById("successMerchPhone").innerText =

                phone.value;



            document.getElementById("successMerchAddress").innerText =

                address.value;



            let noteText = document.getElementById("merchNote").value || "-";



            if(sizeField.style.display !== "none" && size.value !== ""){

                noteText = "Ukuran: " + size.value + (noteText !== "-" ? " | " + noteText : "");

            }



            document.getElementById("successMerchDetail").innerText =

                noteText;



            closeMerchPopup();



        }



        document.getElementById("paymentTotal").innerText =

            "Rp" + finalTotal.toLocaleString("id-ID");



        selectedPaymentMethod = null;



        document.querySelectorAll("#paymentPopup .payment-btn").forEach(btn=>{

            btn.classList.remove("active");

        });



        document

            .getElementById("paymentPopup")

            .classList.add("active");



    }



    /* =========================

       BAYAR

    ========================= */



    function finishPayment(){



      if(!selectedPaymentMethod){



        alert("Silakan pilih metode pembayaran terlebih dahulu.");

        return;



      }



      const button = document.querySelector("#paymentPopup .btn-filled");



      button.disabled = true;

      button.innerText = "MEMPROSES...";



      setTimeout(function(){



        document

          .getElementById("paymentPopup")

          .classList.remove("active");



        const code =

          "SLR-" +

          Math.floor(Math.random()*999999)

          .toString()

          .padStart(6,"0");



if(currentMode==="ticket"){

          if(!savePurchasedTickets(checkoutTicketEmail, checkoutTicketQty)){
            alert("Pembelian dibatalkan: batas 5 tiket per email telah tercapai.");
            button.disabled = false;
            button.innerText = "BAYAR SEKARANG";
            return;
          }

          document.getElementById("ticketQR").innerHTML =



          '<img src="https://cdn.pixabay.com/photo/2023/02/28/01/50/qr-code-7819652_1280.jpg" style="width:200px;height:200px;border:1px solid #000;">';



          document

            .getElementById("successTicketPopup")

            .classList.add("active");



        }



        else{



          document.getElementById("successMerchCode").innerText =

            code;



          document.getElementById("successMerchItem").innerText =

            currentItemName + " x" + currentQty;



          document.getElementById("successMerchTotal").innerText =

            "Rp" + finalTotal.toLocaleString("id-ID");



          document

            .getElementById("successMerchPopup")

            .classList.add("active");



        }



        button.disabled = false;

        button.innerText = "BAYAR SEKARANG";



      },1200);



    }



    /* =====================================

       RESET SEMUA FORM

    ===================================== */



    function resetForms(){



        const forms=document.querySelectorAll("input, textarea, select");



        forms.forEach(el=>{



            if(el.type==="number"){



                el.value=1;



            }



            else{



                el.value="";



            }



        });



    }



    /* =========================

       TUTUP POPUP SUKSES

    ========================= */



    function closeSuccessPopup(type){



        if(type === "ticket"){



            document

                .getElementById("successTicketPopup")

                .classList.remove("active");



        }



        else{



            document

                .getElementById("successMerchPopup")

                .classList.remove("active");



        }



        resetForms();



    }



    /* =========================

       PILIH METODE PEMBAYARAN

    ========================= */



    document.querySelectorAll("#paymentPopup .payment-btn").forEach(btn=>{



        btn.addEventListener("click", function(){



            document.querySelectorAll("#paymentPopup .payment-btn").forEach(b=>{

                b.classList.remove("active");

            });



            btn.classList.add("active");



            selectedPaymentMethod = btn.innerText.trim();



        });



    });









    /* ==========================

       CUSTOM MUSIC PLAYER

    ========================== */



    function formatTime(sec){



        if(isNaN(sec) || !isFinite(sec)) return "0:00";



        const m = Math.floor(sec/60);

        const s = Math.floor(sec%60).toString().padStart(2,"0");



        return m + ":" + s;



    }



    const musicCards = document.querySelectorAll(".music-card");



    musicCards.forEach(card=>{



        const audio = card.querySelector("audio");

        const playBtn = card.querySelector(".play-btn");

        const progressTrack = card.querySelector(".progress-track");

        const progressFill = card.querySelector(".progress-fill");

        const currentTimeEl = card.querySelector(".time-current");

        const durationEl = card.querySelector(".time-duration");



        playBtn.addEventListener("click", function(){



            if(audio.paused){



                musicCards.forEach(otherCard=>{



                    const otherAudio = otherCard.querySelector("audio");

                    const otherBtn = otherCard.querySelector(".play-btn");



                    if(otherAudio !== audio){



                        otherAudio.pause();

                        otherBtn.innerHTML = "▶";



                    }



                });



                audio.play();

                playBtn.innerHTML = "❚❚";



                const videoUrl = card.dataset.video;



                if(videoUrl){



                    openVideoPopup(videoUrl, true);



                }



            } else {



                audio.pause();

                playBtn.innerHTML = "▶";



            }



        });



        audio.addEventListener("timeupdate", function(){



            const percent = (audio.currentTime / audio.duration) * 100 || 0;



            progressFill.style.width = percent + "%";

            currentTimeEl.textContent = formatTime(audio.currentTime);



        });



        audio.addEventListener("loadedmetadata", function(){



            durationEl.textContent = formatTime(audio.duration);



        });



        audio.addEventListener("ended", function(){



            playBtn.innerHTML = "▶";

            progressFill.style.width = "0%";



        });



        progressTrack.addEventListener("click", function(e){



            const rect = progressTrack.getBoundingClientRect();

            const percent = (e.clientX - rect.left) / rect.width;



            if(audio.duration){



                audio.currentTime = percent * audio.duration;



            }



        });



    });







    window.addEventListener("click",function(e){



        [

            "ticketPopup",

            "merchPopup",

            "paymentPopup",

            "successTicketPopup",

            "successMerchPopup",

            "videoPopup"

        ].forEach(id=>{



            const popup=document.getElementById(id);



            if(popup && e.target===popup){



                popup.classList.remove("active");



            }



        });



    });





    document.addEventListener("keydown",function(e){



        if(e.key==="Escape"){



            [

                "ticketPopup",

                "merchPopup",

                "paymentPopup",

                "successTicketPopup",

                "successMerchPopup",

                "videoPopup"

            ].forEach(id=>{



                const popup=document.getElementById(id);



                if(popup){



                    popup.classList.remove("active");



                }



            });



        }



    });







    /* ===========================

       VIDEO POPUP

    =========================== */



    function getYoutubeEmbedUrl(url){



        let videoId = null;



        const shortMatch = url.match(/youtu\.be\/([a-zA-Z0-9_-]+)/);

        const longMatch = url.match(/[?&]v=([a-zA-Z0-9\_-]+)/);

        const embedMatch = url.match(/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/);



        if(shortMatch) videoId = shortMatch[1];

        else if(longMatch) videoId = longMatch[1];

        else if(embedMatch) videoId = embedMatch[1];



        if(!videoId) return null;



        return "https://www.youtube.com/embed/" + videoId + "?autoplay=1";



    }



    function openVideoPopup(video, keepAudioPlaying){



        if(!keepAudioPlaying){



            document.querySelectorAll(".music-card audio").forEach(audio=>{

                audio.pause();

            });



            document.querySelectorAll(".music-card .play-btn").forEach(btn=>{

                btn.innerHTML = "▶";

            });



        }



        const videoTag = document.getElementById("videoPlayer");

        const youtubeTag = document.getElementById("youtubePlayer");



        const isYoutube =

            video.includes("youtube.com") || video.includes("youtu.be");



        if(isYoutube){



            const embedUrl = getYoutubeEmbedUrl(video);

            const muteParam = keepAudioPlaying ? "&mute=1" : "";



            videoTag.pause();

            videoTag.src = "";

            videoTag.classList.add("hidden");



            youtubeTag.src = (embedUrl || "") + muteParam;

            youtubeTag.classList.remove("hidden");



        } else {



            youtubeTag.src = "";

            youtubeTag.classList.add("hidden");



            videoTag.src = video;

            videoTag.muted = !!keepAudioPlaying;

            videoTag.classList.remove("hidden");



        }



        document

            .getElementById("videoPopup")

            .classList.add("active");



    }



    function closeVideoPopup(){



        const videoTag = document.getElementById("videoPlayer");

        const youtubeTag = document.getElementById("youtubePlayer");



        videoTag.pause();

        videoTag.src = "";



        youtubeTag.src = "";



        document

            .getElementById("videoPopup")

            .classList.remove("active");



    }









    /* ===========================

       CONTACT FORM (FORMSPREE)

    =========================== */



    const contactForm = document.getElementById("contactForm");



    if(contactForm){



        contactForm.addEventListener("submit", function(e){



            e.preventDefault();



            const statusEl = document.getElementById("contactStatus");

            const submitBtn = document.getElementById("contactSubmitBtn");



            const originalBtnText = submitBtn.innerText;



            submitBtn.disabled = true;

            submitBtn.innerText = "MENGIRIM...";



            statusEl.classList.add("hidden");



            fetch(contactForm.action, {



                method: "POST",

                body: new FormData(contactForm),

                headers: {

                    "Accept": "application/json"

                }



            })

            .then(function(response){



                if(response.ok){



                    statusEl.textContent =

                        "Pesan berhasil terkirim! Terima kasih sudah menghubungi kami.";



                    statusEl.className =

                        "text-center font-bold text-green-400";



                    contactForm.reset();



                } else {



                    statusEl.textContent =

                        "Gagal mengirim pesan. Silakan coba lagi nanti.";



                    statusEl.className =

                        "text-center font-bold text-red-500";



                }



            })

            .catch(function(){



                statusEl.textContent =

                    "Gagal mengirim pesan. Periksa koneksi internet kamu.";



                statusEl.className =

                    "text-center font-bold text-red-500";



            })

            .finally(function(){



                submitBtn.disabled = false;

                submitBtn.innerText = originalBtnText;



            });



        });



    }









    /* ===========================

       MOBILE MENU TOGGLE

    =========================== */



    const menuButton = document.getElementById("menuButton");

    const mobileMenu = document.getElementById("mobileMenu");



    if(menuButton && mobileMenu){



        menuButton.addEventListener("click", function(){



            mobileMenu.classList.toggle("hidden");



        });



        document.querySelectorAll("#mobileMenu a").forEach(function(link){



            link.addEventListener("click", function(){



                mobileMenu.classList.add("hidden");



            });



        });



    }




/* =========================
   SEARCH + PAGINATION LAGU
========================= */
(function initSongListing(){
  const list = document.getElementById("song-list");
  const search = document.getElementById("songSearch");
  if(!list || !search) return;
  const cards = Array.from(list.querySelectorAll(".music-card"));
  const pageSize = 6;
  let currentPage = 1;
  let filteredCards = cards;
  const pagination = document.createElement("div");
  pagination.id = "songPagination";
  pagination.className = "music-pagination";
  list.insertAdjacentElement("afterend", pagination);
  const info = document.getElementById("songResultInfo");

  function render(){
    const totalPages = Math.max(1, Math.ceil(filteredCards.length / pageSize));
    currentPage = Math.min(currentPage, totalPages);
    cards.forEach(card => { card.hidden = true; });
    const start = (currentPage - 1) * pageSize;
    filteredCards.slice(start, start + pageSize).forEach(card => { card.hidden = false; });
    if(info) info.textContent = `${filteredCards.length} lagu ditemukan`;
    pagination.innerHTML = "";
    if(totalPages <= 1) return;

    const prev = document.createElement("button");
    prev.type = "button"; prev.textContent = "‹ Sebelumnya";
    prev.disabled = currentPage === 1;
    prev.addEventListener("click", () => { currentPage--; render(); });
    pagination.appendChild(prev);

    for(let page=1; page<=totalPages; page++){
      const button = document.createElement("button");
      button.type = "button"; button.textContent = String(page);
      button.classList.toggle("active", page === currentPage);
      button.setAttribute("aria-label", `Halaman ${page}`);
      button.addEventListener("click", () => { currentPage = page; render(); });
      pagination.appendChild(button);
    }

    const next = document.createElement("button");
    next.type = "button"; next.textContent = "Berikutnya ›";
    next.disabled = currentPage === totalPages;
    next.addEventListener("click", () => { currentPage++; render(); });
    pagination.appendChild(next);
  }

  search.addEventListener("input", () => {
    const query = search.value.trim().toLowerCase();
    filteredCards = cards.filter(card => card.textContent.toLowerCase().includes(query));
    currentPage = 1;
    render();
  });
  render();
})();

/* =========================
   TAB GALERI VIDEO / FOTO
========================= */
(function initGalleryTabs(){
  const tabs = document.querySelectorAll("[data-gallery-tab]");
  if(!tabs.length) return;
  tabs.forEach(tab => tab.addEventListener("click", () => {
    const selected = tab.dataset.galleryTab;
    tabs.forEach(item => {
      const active = item === tab;
      item.classList.toggle("active", active);
      item.setAttribute("aria-selected", active ? "true" : "false");
    });
    document.querySelectorAll(".gallery-panel").forEach(panel => {
      const active = panel.id === `gallery-${selected}`;
      panel.hidden = !active;
      panel.classList.toggle("active", active);
    });
  }));
})();

// Perbarui kuota saat email atau jumlah tiket berubah.
{
  const email = document.getElementById("ticketEmail");
  const amount = document.getElementById("ticketAmount");
  if(email) email.addEventListener("input", updateTicketLimit);
  if(amount) amount.addEventListener("input", () => {
    const emailField = document.getElementById("ticketEmail");
    const remaining = getRemainingTickets(emailField ? emailField.value : "");
    if((parseInt(amount.value, 10) || 1) > remaining) amount.value = remaining || 1;
    updateTotal();
  });
}
