<?php
$user_profile = ['Nama' => '', 'email' => '', 'Nomor_telepon' => '', 'alamat' => ''];
if (isset($_SESSION['id_pengguna'])) {
    $uid = $_SESSION['id_pengguna'];
    $u_res = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$uid'");
    if ($u_row = mysqli_fetch_assoc($u_res)) {
        $user_profile = $u_row;
    }
}
?>

<script>
  const profileData = <?= json_encode($user_profile); ?>;

  document.addEventListener('DOMContentLoaded', () => {
    if (profileData.Nama) {
      // Pop-up Tiket
      if (document.getElementById('ticketName')) document.getElementById('ticketName').value = profileData.Nama;
      if (document.getElementById('ticketEmail')) document.getElementById('ticketEmail').value = profileData.email;
      
      // Pop-up Merch
      if (document.getElementById('merchName')) document.getElementById('merchName').value = profileData.Nama;
      if (document.getElementById('merchPhone')) document.getElementById('merchPhone').value = profileData.Nomor_telepon;
      if (document.getElementById('merchAddress')) document.getElementById('merchAddress').value = profileData.alamat;
    }
  });
  
</script>
<!-- FOOTER -->
  <footer class="text-center p-6 border-t border-yellow-400 text-yellow-400">
    SLR © <?php echo date('Y'); ?>
  </footer>

  <!-- SCRIPT -->
  <script src="script_updated.js"></script>

</body>
</html>