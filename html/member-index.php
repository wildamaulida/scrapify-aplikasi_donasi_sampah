<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SCRAPIFY</title>
    <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.11.4/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/scrollmagic@2.0.8/scrollmagic/minified/ScrollMagic.min.js"></script>
    <style>
      /* Enhanced Custom Animations */
      @keyframes float {
        0% {
          transform: translateY(0px) rotate(0deg);
        }
        50% {
          transform: translateY(-20px) rotate(3deg);
        }
        100% {
          transform: translateY(0px) rotate(-3deg);
        }
      }

      @keyframes pulse {
        0% {
          transform: scale(1);
        }
        50% {
          transform: scale(1.05);
        }
        100% {
          transform: scale(1);
        }
      }

      .animate-float {
        animation: float 3s ease-in-out infinite;
      }

      .animate-pulse {
        animation: pulse 2s ease-in-out infinite;
      }

      .animate-slide-in {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 0.8s, transform 0.8s;
      }

      .animate-slide-in.active {
        opacity: 1;
        transform: translateY(0);
      }

      .hero-bg {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        position: relative;
        overflow: hidden;
        background-image: linear-gradient(
            rgba(0, 0, 0, 0.2),
            rgba(0, 0, 0, 0.2)
          ),
          url("https://images.unsplash.com/photo-1606037150583-fb842a55bae7?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
        background-size: cover;
        background-position: center;
      }

      .hero-bg::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 1;
      }

      /* Typing Effect */
      .typing-demo {
        width: 20ch;
        animation: typing 2s steps(22), blink 0.5s step-end infinite alternate;
        white-space: nowrap;
        overflow: hidden;
        border-right: 1px solid;
        font-family: monospace;
        font-size: 2em;
      }

      @keyframes typing {
        from {
          width: 0;
        }
      }

      @keyframes blink {
        50% {
          border-color: transparent;
        }
      }

      /* Particle Background */
      #particles-js {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 1;
      }

      .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .logo {
        width: 100px;
        height: 100px;
        background-color: transparent;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .logo img {
        max-width: 150%;
        max-height: 150%;
      }

      /* Updated Typing Effect */
      .typing-demo {
        width: 22ch;
        margin: 0 auto; /* Center the typing text */
        animation: typing 2s steps(22), blink 0.5s step-end infinite alternate;
        white-space: nowrap;
        overflow: hidden;
        border-right: 2px solid; /* Reduced border width */
        font-family: monospace;
        font-size: 2em;
      }

      @keyframes typing {
        from {
          width: 0;
        }
      }

      @keyframes blink {
        50% {
          border-color: transparent;
        }
      }

      /* Enhanced Hover Effects */
      .card {
        transition: all 0.3s ease;
      }

      .card:hover {
        transform: scale(1.05) translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
      }

      .card:hover .card-badge {
        background-color: #48bb78; /* More vibrant green for hover */
      }

      .card-image {
        transition: transform 0.3s ease;
      }

      .card:hover .card-image {
        transform: scale(1.1) rotate(3deg);
      }

      /* Button Elevation on Hover */
      .hover-elevate {
        transition: all 0.3s ease;
      }

      .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
      }
    </style>
  </head>

  <body class="bg-gray-100">
    <!--- navbar --->
    <?php include 'member-navbar.php'; ?>

    <!-- Hero Section with Animated Text -->
    <section class="hero-bg text-white py-20 relative">
      <div id="particles-js" class="absolute inset-0"></div>
      <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
        <div class="logo-container">
          <div class="logo">
            <img
              src="../images/logo2.png"
              alt="Scrapify Logo"
              class="animate-pulse"
            />
          </div>
        </div>
        <h2
          id="hero-title"
          class="text-4xl font-bold mb-4 opacity-0 typing-demo"
        >
          SCRAPIFY
        </h2>
        <p id="hero-subtitle" class="mb-8 text-lg opacity-0">
          Bergabunglah dengan kami untuk menciptakan lingkungan yang lebih
          bersih dan hijau.
        </p>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-100">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-10 animate-slide-in">
          Apa yang Ingin Anda Lakukan?
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
          <a
            href="../html/member-manajemen_donasi.php"
            class="block bg-white p-6 rounded-lg shadow-lg text-center card hover:shadow-xl transform hover:scale-105 transition duration-300 animate-slide-in"
          >
            <div class="relative">
              <img
                src="../images/setorSampah.jpeg"
                alt="Donasi Sampah"
                class="w-full h-40 object-cover rounded-t-lg mb-4 animate-float"
              />
              <div
                class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs"
              >
                Baru
              </div>
            </div>
            <h3 class="text-xl font-semibold mb-3">Donasi Sampah</h3>
            <p>
              Layanan pengelolaan sampah yang dapat diandalkan dengan opsi
              pengantaran langsung atau penjemputan sampah.
            </p>
          </a>
          <a
            href="../html/member-edukasi.php"
            class="block bg-white p-6 rounded-lg shadow-lg text-center card hover:shadow-xl transform hover:scale-105 transition duration-300 animate-slide-in"
          >
            <div class="relative">
              <img
                src="../images/askAi.jpg"
                alt="Tanya AI"
                class="w-full h-40 object-cover rounded-t-lg mb-4 animate-float"
              />
              <div
                class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded-full text-xs"
              >
                AI Powered
              </div>
            </div>
            <h3 class="text-xl font-semibold mb-3">Tanya AI</h3>
            <p>
              Dengan teknologi AI, Anda dapat mengajukan berbagai pertanyaan dan
              mendapatkan jawaban atau saran terkit pengelolaan sampah.
            </p>
          </a>
          <a
            href="../html/member-berita.php"
            class="block bg-white p-6 rounded-lg shadow-lg text-center card hover:shadow-xl transform hover:scale-105 transition duration-300 animate-slide-in"
          >
            <div class="relative">
              <img
                src="../images/event.jpg"
                alt="Event"
                class="w-full h-40 object-cover rounded-t-lg mb-4 animate-float"
              />
              <div
                class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs"
              >
                Populer
              </div>
            </div>
            <h3 class="text-xl font-semibold mb-3">Lihat Berita</h3>
            <p>
              Lihat Berita terbaru agar Anda tetap updated tentang acara dan kampanye
              pengelolaan sampah, termasuk seminar, lokakarya, dan kegiatan
              bersih-bersih lingkungan.
            </p>
          </a>
        </div>
      </div>
    </section>

    <!-- Tentang Scrapify Section -->
    <section id="about-scrapify" class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-10 animate-slide-in">
          Tentang Scrapify
        </h2>
        <div class="grid md:grid-cols-2 gap-10 items-center">
          <div class="animate-slide-in">
            <h3 class="text-2xl font-semibold mb-4 text-green-600">
              Solusi Pintar Pengelolaan Sampah
            </h3>
            <p class="text-gray-700 mb-4">
              Scrapify adalah platform inovatif yang bertujuan untuk mengubah
              cara masyarakat mengelola dan mendaur ulang sampah. Kami
              menyediakan solusi komprehensif yang memudahkan setiap individu
              untuk berkontribusi pada lingkungan yang lebih bersih dan
              berkelanjutan.
            </p>
            <ul class="space-y-3 text-gray-600 mb-6">
              <li class="flex items-center">
                <i class="fas fa-recycle text-green-500 mr-3"></i>
                Donasi Sampah Mudah dan Cepat
              </li>
              <li class="flex items-center">
                <i class="fas fa-robot text-blue-500 mr-3"></i>
                Konsultasi Cerdas dengan AI
              </li>
              <li class="flex items-center">
                <i class="fas fa-globe text-teal-500 mr-3"></i>
                Edukasi Lingkungan Berkelanjutan
              </li>
            </ul>
          </div>
          <div class="animate-slide-in">
            <div class="bg-green-100 p-6 rounded-lg shadow-lg">
              <h4 class="text-xl font-semibold mb-4 text-green-700">
                Hubungi Kami
              </h4>
              <div class="space-y-3">
                <p class="flex items-center">
                  <i class="fas fa-envelope text-green-500 mr-3"></i>
                  Email: support@scrapify.id
                </p>
                <p class="flex items-center">
                  <i class="fas fa-phone text-green-500 mr-3"></i>
                  Telepon: +62 812-3456-7890
                </p>
                <p class="flex items-center">
                  <i class="fas fa-map-marker-alt text-green-500 mr-3"></i>
                  Alamat: Jl. Hijau Berkelanjutan No. 42, Jakarta
                </p>
                <div class="flex space-x-4 mt-4">
                  <a href="#" class="text-green-600 hover:text-green-800"
                    ><i class="fab fa-instagram text-2xl"></i
                  ></a>
                  <a href="#" class="text-green-600 hover:text-green-800"
                    ><i class="fab fa-facebook text-2xl"></i
                  ></a>
                  <a href="#" class="text-green-600 hover:text-green-800"
                    ><i class="fab fa-twitter text-2xl"></i
                  ></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
      // Particle JS Configuration
      particlesJS("particles-js", {
        particles: {
          number: { value: 80, density: { enable: true, value_area: 800 } },
          color: { value: "#ffffff" },
          shape: { type: "circle" },
          opacity: { value: 0.5, random: false },
          size: { value: 3, random: true },
          line_linked: {
            enable: true,
            distance: 150,
            color: "#ffffff",
            opacity: 0.4,
            width: 1,
          },
          move: {
            enable: true,
            speed: 6,
            direction: "none",
            random: false,
            straight: false,
            out_mode: "out",
            bounce: false,
          },
        },
        interactivity: {
          detect_on: "canvas",
          events: {
            onhover: { enable: true, mode: "repulse" },
            onclick: { enable: true, mode: "push" },
            resize: true,
          },
          modes: {
            grab: { distance: 400, line_linked: { opacity: 1 } },
            bubble: {
              distance: 400,
              size: 40,
              duration: 2,
              opacity: 8,
              speed: 3,
            },
            repulse: { distance: 200, duration: 0.4 },
            push: { particles_nb: 4 },
            remove: { particles_nb: 2 },
          },
        },
        retina_detect: true,
      });

      document.addEventListener("DOMContentLoaded", () => {
        const heroTitle = document.getElementById("hero-title");
        const heroSubtitle = document.getElementById("hero-subtitle");
        gsap.fromTo(
          heroTitle,
          { opacity: 0, y: 50 },
          { opacity: 1, y: 0, duration: 1, ease: "power3.out" }
        );
        gsap.fromTo(
          heroSubtitle,
          { opacity: 0, y: 50 },
          { opacity: 1, y: 0, duration: 1, delay: 0.5, ease: "power3.out" }
        );

        const slideInElements = document.querySelectorAll(".animate-slide-in");
        const observer = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                entry.target.classList.add("active");
              }
            });
          },
          { threshold: 0.1 }
        );

        slideInElements.forEach((el) => observer.observe(el));
      });
    </script>
  </body>
</html>
