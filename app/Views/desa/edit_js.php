<!-- Select2 -->
<script src="<?= base_url('plugins/select2/js/select2.full.min.js') ?>"></script>
<script>
  $(function() {
    // Initialize Select2 Elements
    $('.select2').select2({
      theme: 'bootstrap4'
    });

    // Tambah Kecamatan Baru Button
    $('#kecamatan').on('select2:open', function() {
      if (!$('#btn-tambah-kecamatan-baru').length) {
        $('.select2-results:not(:has(a))').append('<a href="#" id="btn-tambah-kecamatan-baru" class="btn btn-sm btn-primary w-100 mt-1">+ Tambah Kecamatan Baru</a>');
      }
    });

    // Handle Tambah Kecamatan Baru
    $(document).on('click', '#btn-tambah-kecamatan-baru', function(e) {
      e.preventDefault();
      $('#kecamatan').select2('close');
      $('#kecamatan-baru-container').show();
    });

    // Tambah Kecamatan Baru
    $('#tambah-kecamatan').click(function() {
      let kodeKecamatan = $('#kode_kecamatan_baru').val();
      let namaKecamatan = $('#nama_kecamatan_baru').val();

      if (kodeKecamatan && namaKecamatan) {
        let newOption = new Option(namaKecamatan, namaKecamatan, true, true);
        $('#kecamatan').append(newOption).trigger('change');
        $('#kode_kecamatan').val(kodeKecamatan);
        $('#kecamatan-baru-container').hide();
        $('#kode_kecamatan_baru').val('');
        $('#nama_kecamatan_baru').val('');
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Kode dan Nama Kecamatan harus diisi!',
        });
      }
    });

    // Load Kabupaten based on Provinsi
    $('#provinsi').change(function() {
      let provinsi = $(this).val();
      if (provinsi) {
        $.ajax({
          url: '<?= base_url('desaController/getKabupaten') ?>',
          type: 'post',
          data: {
            provinsi: provinsi
          },
          dataType: 'json',
          success: function(response) {
            $('#kabupaten').empty();
            $('#kabupaten').append('<option value="">Pilih Kabupaten</option>');
            $.each(response, function(key, value) {
              $('#kabupaten').append('<option value="' + value.nama_kabupaten + '" data-kode="' + value.kode_kabupaten + '">' + value.nama_kabupaten + '</option>');
            });
          }
        });
      } else {
        $('#kabupaten').empty();
        $('#kabupaten').append('<option value="">Pilih Kabupaten</option>');
      }
    });

    // Load Kecamatan based on Kabupaten
    $('#kabupaten').change(function() {
      let kabupaten = $(this).val();
      if (kabupaten) {
        let kodeKabupaten = $(this).find(':selected').data('kode');
        $('#kode_kabupaten').val(kodeKabupaten);

        $.ajax({
          url: '<?= base_url('desaController/getKecamatan') ?>',
          type: 'post',
          data: {
            kabupaten: kabupaten
          },
          dataType: 'json',
          success: function(response) {
            $('#kecamatan').empty();
            $('#kecamatan').append('<option value="">Pilih Kecamatan</option>');
            $.each(response, function(key, value) {
              $('#kecamatan').append('<option value="' + value.nama_kecamatan + '" data-kode="' + value.kode_kecamatan + '">' + value.nama_kecamatan + '</option>');
            });
          }
        });
      } else {
        $('#kecamatan').empty();
        $('#kecamatan').append('<option value="">Pilih Kecamatan</option>');
      }
    });

    // Update kode_kecamatan when kecamatan changes
    $('#kecamatan').change(function() {
      let kodeKecamatan = $(this).find(':selected').data('kode');
      $('#kode_kecamatan').val(kodeKecamatan);
    });

    // Form submission confirmation
    $('#form-edit-desa').on('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Simpan perubahan?',
        text: 'Pastikan perubahan sudah benar sebelum disimpan!',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  });
</script>