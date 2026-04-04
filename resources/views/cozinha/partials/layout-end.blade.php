  </main>
</div>
<script>
function updateSidebarClock() {
  const el = document.getElementById('sidebarClock');
  if (!el) return;
  el.textContent = new Date().toLocaleTimeString('pt-BR');
}
updateSidebarClock();
setInterval(updateSidebarClock, 1000);
</script>
</body>
</html>
