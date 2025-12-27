$(function () {
  // Search shortcut (Ctrl+K)
  $(document).on("keydown", (e) => {
    if (e.key === "k" && e.ctrlKey) {
      e.preventDefault();
      e.stopPropagation();
      $("#search").trigger("focus");
    }
  });

  // Sidebar drawer toggle
  $(".drawer-btn").on("click", () => {
    const wrapper = $(".layout-wrapper");
    const overlay = $(".aside-overlay");

    if (wrapper.hasClass("active")) {
      wrapper.removeClass("active");
      overlay.addClass("hidden");
    } else {
      wrapper.addClass("active");
      // Show overlay on mobile
      if (window.innerWidth < 1280) {
        overlay.removeClass("hidden");
      }
    }
  });

  // Close sidebar when clicking overlay
  $(".aside-overlay").on("click", () => {
    $(".layout-wrapper").removeClass("active");
    $(".aside-overlay").addClass("hidden");
  });

  // Sidebar toggle shortcut (Ctrl+B)
  $(document).on("keydown", (e) => {
    if (e.key === "b" && e.ctrlKey) {
      e.preventDefault();
      e.stopPropagation();
      const wrapper = $(".layout-wrapper");
      if (wrapper.hasClass("active")) {
        wrapper.removeClass("active");
      } else {
        wrapper.addClass("active");
      }
    }
  });

  // Handle window resize
  $(window).on("resize", () => {
    if (window.innerWidth >= 1280) {
      $(".aside-overlay").addClass("hidden");
    }
  });
});

// Quill Editor
function QuillIsExists() {
  const editorOne = document.querySelector("#editor");
  const editorTwo = document.querySelector("#editor2");
  var toolbarOptions = [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    ["bold", "italic", "underline"],
    [{ list: "ordered" }, { list: "bullet" }],
    ["link", "image"],
  ];
  if (editorOne) {
    new Quill("#editor", {
      modules: { toolbar: toolbarOptions },
      theme: "snow",
    });
  } else if (editorTwo) {
    new Quill("#editor2", {
      modules: { toolbar: "#toolbar2" },
      theme: "snow",
    });
  }
}
QuillIsExists();

// Settings Tab
const tabs = document.querySelectorAll(".tab");
const tabContent = document.querySelectorAll(".tab-pane");

tabs.forEach((tab) => {
  tab.addEventListener("click", () => {
    const target = tab.getAttribute("data-tab");
    tabs.forEach((t) => t.classList.remove("active"));
    tab.classList.add("active");
    tabContent.forEach((content) => {
      content.classList.toggle("active", content.getAttribute("id") === target);
    });
  });
});

// FAQ Accordion
const accordionHeader = document.querySelectorAll(".accordion-header");
accordionHeader.forEach((header) => {
  header.addEventListener("click", function () {
    const accordionContent = header.parentElement.querySelector(".accordion-content");
    let accordionMaxHeight = accordionContent.style.maxHeight;

    if (accordionMaxHeight == "0px" || accordionMaxHeight.length == 0) {
      accordionContent.style.maxHeight = `${accordionContent.scrollHeight + 32}px`;
      header.querySelector(".fas")?.classList.remove("fa-plus");
      header.querySelector(".fas")?.classList.add("fa-minus");
      header.querySelector(".title")?.classList.add("font-bold");
    } else {
      accordionContent.style.maxHeight = `0px`;
      header.querySelector(".fas")?.classList.add("fa-plus");
      header.querySelector(".fas")?.classList.remove("fa-minus");
      header.querySelector(".title")?.classList.remove("font-bold");
    }
  });
});

// Dropdown Actions
function notificationAction() {
  $("#notification-box").toggle();
  $("#noti-outside").toggle();
}
function messageAction() {
  $("#message-box").toggle();
  $("#mes-outside").toggle();
}
function storeAction() {
  $("#store-box").toggle();
  $("#store-outside").toggle();
}
function profileAction() {
  $(".profile-box").toggle();
  $(".profile-outside").toggle();
}
function toggleSettings() {
  $("#profile-settings").toggle();
}
function dateFilterAction(selector) {
  $(selector).toggle();
}

// Multi Step Modal
function ModalExist() {
  const modalContent = document.querySelector(".modal-content");
  if (!modalContent) return;

  const modal = document.getElementById("multi-step-modal");
  const stepContents = modalContent.querySelectorAll(".step-content");
  const nextButtons = modalContent.querySelectorAll('[id$="-next"]');
  const cancelButtons = modalContent.querySelectorAll('[id$="-cancel"]');
  const modalOpen = document.querySelector(".modal-open");
  const modalOverlay = document.querySelector(".modal-overlay");

  if (modalOpen) {
    modalOpen.addEventListener("click", () => modal.classList.remove("hidden"));
  }

  function hideModal() {
    modal.classList.add("hidden");
  }

  if (modalOverlay) {
    modalOverlay.addEventListener("click", hideModal);
  }

  let currentStep = 1;

  function showStep(step) {
    stepContents.forEach((sc) => sc.classList.add("hidden"));
    modalContent.querySelector(`.step-${step}`)?.classList.remove("hidden");
  }

  function setCurrentStep(step) {
    currentStep = step;
    showStep(currentStep);
  }

  cancelButtons.forEach((btn) => {
    btn.addEventListener("click", () => modal.classList.add("hidden"));
  });

  nextButtons.forEach((btn) => {
    btn.addEventListener("click", () => setCurrentStep(currentStep + 1));
  });

  setCurrentStep(1);
}
ModalExist();

// Switch Toggle
const switch_btn = document.querySelectorAll(".switch-btn");
if (switch_btn && switch_btn.length > 0) {
  switch_btn.forEach((item) => {
    item.addEventListener("click", () => {
      item.classList.toggle("active");
    });
  });
}

// Navigation Submenu
function navSubmenu() {
  const navSelector = document.querySelector(".nav-wrapper");
  if (!navSelector) return;

  const navItems = navSelector.querySelectorAll(".item");
  if (!navItems || navItems.length === 0) return;

  navItems.forEach((item) => {
    const submenuExist = item.querySelector(".sub-menu");
    if (submenuExist) {
      const clickItem = item.querySelector("a");
      clickItem.addEventListener("click", (e) => {
        e.preventDefault();
        submenuExist.classList.toggle("active");
      });
    }
  });
}
navSubmenu();

// ============================================
// Theme Toggle (Dark/Light Mode)
// ============================================
function initTheme() {
  // Check for saved theme preference or system preference
  const savedTheme = localStorage.getItem('theme');
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

  if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
}

function toggleTheme() {
  const isDark = document.documentElement.classList.contains('dark');

  if (isDark) {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  } else {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  }
}

// Initialize theme on page load
initTheme();

// Theme toggle button click handlers
document.addEventListener('DOMContentLoaded', function() {
  const themeToggle = document.getElementById('theme-toggle');
  const themeToggleMobile = document.getElementById('theme-toggle-mobile');
  const themeToggleSidebar = document.getElementById('theme-toggle-sidebar');

  if (themeToggle) {
    themeToggle.addEventListener('click', toggleTheme);
  }

  if (themeToggleMobile) {
    themeToggleMobile.addEventListener('click', toggleTheme);
  }

  if (themeToggleSidebar) {
    themeToggleSidebar.addEventListener('click', toggleTheme);
  }
});

// Listen for system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
  if (!localStorage.getItem('theme')) {
    if (e.matches) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }
});
