<!--   Core JS Files   -->
<script src="{{ asset('azzara/assets/js/core/jquery.3.2.1.min.js') }}"></script>
<script src="{{ asset('azzara/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('azzara/assets/js/core/bootstrap.min.js') }}"></script>

<!-- jQuery UI -->
<script src="{{ asset('azzara/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ asset('azzara/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>

<!-- jQuery Scrollbar -->
<script src="{{ asset('azzara/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

<!-- Moment JS -->
<script src="{{ asset('azzara/assets/js/plugin/moment/moment.min.js') }}"></script>

<!-- Chart JS -->
<script src="{{ asset('azzara/assets/js/plugin/chart.js/chart.min.js') }}"></script>

<!-- jQuery Sparkline -->
<script src="{{ asset('azzara/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

<!-- Chart Circle -->
<script src="{{ asset('azzara/assets/js/plugin/chart-circle/circles.min.js') }}"></script>

<!-- Datatables -->
<script src="{{ asset('azzara/assets/js/plugin/datatables/datatables.min.js') }}"></script>

<!-- Bootstrap Notify -->
<script src="{{ asset('azzara/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

<!-- Bootstrap Toggle -->
<script src="{{ asset('azzara/assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>

<!-- jQuery Vector Maps -->
<script src="{{ asset('azzara/assets/js/plugin/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('azzara/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js') }}"></script>

<!-- Google Maps Plugin -->
<script src="{{ asset('azzara/assets/js/plugin/gmaps/gmaps.js') }}"></script>

<!-- Sweet Alert -->
<script src="{{ asset('azzara/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Azzara JS -->
<script src="{{ asset('azzara/assets/js/ready.min.js') }}"></script>

<!-- Azzara DEMO methods, don't include it in your project! -->
<script src="{{ asset('azzara/assets/js/setting-demo.js') }}"></script>
{{-- <script src="{{ asset('azzara/assets/js/demo.js') }}"></script> --}}

<!-- chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- datatables -->
<script>
    $(document).ready(function() {
        $('#basic-datatables').DataTable({});

        $('#multi-filter-select').DataTable({
            "pageLength": 5,
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var select = $(
                            '<select class="form-control"><option value=""></option></select>'
                            )
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d +
                            '</option>')
                    });
                });
            }
        });

        // Add Row
        $('#add-row').DataTable({
            "pageLength": 5,
        });

        var action =
            '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $('#addRowButton').click(function() {
            $('#add-row').dataTable().fnAddData([
                $("#addName").val(),
                $("#addPosition").val(),
                $("#addOffice").val(),
                action
            ]);
            $('#addRowModal').modal('hide');

        });
    });
</script>

<!-- SweetAlert simpan data -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const saveButtons = document.querySelectorAll('.saveButton');

        saveButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const form = button.closest('form');

                if (!form) {
                    console.error("Form tidak ditemukan!");
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data akan disimpan!',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Tidak, batalkan!',
                    confirmButtonText: 'Ya, simpan!',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    reverseButtons: true
                }).then((willSave) => {
                    if (willSave.isConfirmed) {
                        form.submit(); // Form langsung disubmit tanpa SweetAlert kedua
                    }
                });
            });
        });
    });
</script>

<!-- SweetAlert hapus data -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.deleteButton');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const formId = this.getAttribute('data-form-id');
                const form = document.getElementById(formId);

                if (!form) {
                    console.error('Form tidak ditemukan:', formId);
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#28a745',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {
                        Swal.fire({
                            title: 'Dibatalkan',
                            text: 'Data Anda aman.',
                            icon: 'info',
                            confirmButtonColor: '#007bff'
                        });
                    }
                });
            });
        });
    });
</script>

<!-- chart -->
@isset($suhuKelembabanChart)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const suhuKelembabanData = @json($suhuKelembabanChart);

        const canvas = document.getElementById('statisticsChart');
        if (!canvas) {
            console.error('Canvas statisticsChart tidak ditemukan!');
            return;
        }

        const ctx = canvas.getContext('2d');

        const labels = suhuKelembabanData.map(item => item.label);
        const suhuData = suhuKelembabanData.map(item => item.suhu);
        const kelembabanData = suhuKelembabanData.map(item => item.kelembaban);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Suhu (°C)',
                        data: suhuData,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Kelembaban (%)',
                        data: kelembabanData,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 100
                    }
                }
            }
        });
    });
</script>
@endisset


</body>
</html>
