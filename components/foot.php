        </div>
        </div>

        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/app.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function() {
                $('.datatable').DataTable();
            });
        </script>
        <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
        <script>
            let optionsVisitorsProfile = {
                series: [<?=$amountAssigned?>, <?=$amountInProgress?>, <?=$amountPending?>, <?=$amountCompleted?>, <?=$amountOverdue?>],
                labels: ["Assigned", "In Progress", "Pending", "Completed", "Overdue", ],
                colors: ["#435ebe", "#55c6e8", "#00c6e8",  "#0077e8",  "#0010e8"],
                chart: {
                    type: "donut",
                    width: "100%",
                    height: "350px",
                },
                legend: {
                    position: "bottom",
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "30%",
                        },
                    },
                },
            }
            var chartVisitorsProfile = new ApexCharts(
                document.getElementById("chart-visitors-profile"),
                optionsVisitorsProfile
            )

            chartVisitorsProfile.render()
        </script>
        </body>

        </html>