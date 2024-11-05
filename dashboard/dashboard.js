const hamIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" width="24"
                height="24" stroke="white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>`;
const closeIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"  width="24"
                height="24" stroke="white">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
`;
const sidebar = document.querySelector(".sidebar");
const sidebarToggler = document.querySelector("button.sidebar-toggler");
sidebarToggler.addEventListener("click", () => {
  if (sidebar.classList.contains("active")) {
    sidebar.classList.remove("active");
    sidebarToggler.innerHTML = hamIcon;
  } else {
    sidebar.classList.add("active");
    sidebarToggler.innerHTML = closeIcon;
  }
});
