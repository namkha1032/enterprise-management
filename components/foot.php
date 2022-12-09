        </div>
        </div>

        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/app.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function() {
                $('.datatable').DataTable({
                    scrollY: '250px',
                    scrollCollapse: true,
                    paging: false,
                    info: false,
                    "autoWidth": false
                });
            });
        </script>
        <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
        <?php if ($_GET['page'] == 'task') { ?>
            <script>
                // Task pie chart
                let taskDonutConfig = {
                    series: [<?= $amountAssigned ?>, <?= $amountInProgress ?>, <?= $amountPending ?>, <?= $amountCompleted ?>, <?= $amountOverdue ?>],
                    labels: ["Assigned", "In Progress", "Pending", "Completed", "Overdue", ],
                    colors: ["#435ebe", "#55c6e8", "#00c6e8", "#0077e8", "#0010e8"],
                    chart: {
                        type: "donut",
                        width: "100%",
                        height: "400px",
                    },
                    legend: {
                        position: "bottom",
                        fontSize: "20px"
                    },
                    dataLabels: {
                        enabled: true,
                        offsetX: -6,
                        style: {
                            fontSize: '20px',
                            colors: ['#fff']
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: "30%",
                            },
                        },
                    },
                }
                var taskDonutChart = new ApexCharts(
                    document.getElementById("chart-visitors-profile"),
                    taskDonutConfig
                )
                // Task bar chart
                let dataIncompleteArray = <?php echo json_encode($incompletedArray); ?>;
                let dataCompleteArray = <?php echo json_encode($completedArray); ?>;
                let dataOverdueArray = <?php echo json_encode($overdueArray); ?>;
                let nameArray = <?php echo json_encode($nameArray); ?>;
                let taskBarConfig = {
                    series: [{
                        name: ['Incompleted'],
                        data: dataIncompleteArray
                    }, {
                        name: ['Completed'],
                        data: dataCompleteArray
                    }, {
                        name: ['Overdue'],
                        data: dataOverdueArray
                    }],
                    colors: ["#FAD501", "#13E000", "#F05800"],
                    chart: {
                        type: 'bar',
                        height: 430
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            dataLabels: {
                                position: 'top',
                            },
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetX: -6,
                        style: {
                            fontSize: '16px',
                            colors: ['#fff']
                        }
                    },
                    stroke: {
                        show: true,
                        width: 1,
                        colors: ['#fff']
                    },
                    tooltip: {
                        shared: true,
                        intersect: false
                    },
                    legend: {
                        fontSize: "20px"
                    },
                    xaxis: {
                        categories: nameArray,
                        labels: {
                            style: {
                                fontSize: '20px'
                            }
                        }
                    },
                }
                var taskBarChart = new ApexCharts(document.getElementById("task-bar-chart"), taskBarConfig);
                
                taskDonutChart.render();
                taskBarChart.render();
            </script>
        <?php } ?>
        <?php if ($_GET['page'] == 'request2') { ?>
            <script>
                // request Pie chart
                let requestPieConfig = {
                    series: [70, 30],
                    labels: ["Male", "Female"],
                    colors: ["#435ebe", "#55c6e8"],
                    chart: {
                        type: "pie",
                        width: "100%",
                        height: "350px",
                    },
                    legend: {
                        position: "bottom",
                    },
                }
                var requestPieChart = new ApexCharts(document.getElementById("request-pie-chart"), requestPieConfig)
                requestPieChart.render();
            </script>

        <?php } ?>
        </body>

        </html>