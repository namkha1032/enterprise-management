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
                var reqTable = $('.reqTable').DataTable({
                    scrollY: '650px',
                    scrollCollapse: true,
                    paging: false,
                    info: false,
                    "autoWidth": false
                });
            });
        </script>
        <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
        <?php if (isset($_GET['page']) && $_GET['page'] == 'taskmanage') { ?>
            <script>
                // Task pie chart
                let taskDonutConfig = {
                    series: [<?= $amountAssigned ?>, <?= $amountInProgress ?>, <?= $amountPending ?>, <?= $amountCompleted ?>, <?= $amountOverdue ?>],
                    labels: ["Assigned", "In Progress", "Pending", "Completed", "Overdue", ],
                    colors: ["#ffce54", "#ac92eb", "#4fc1e8", "#a0d568", "#ed5564"],
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
                    document.getElementById("task-donut-chart"),
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
                    colors: ["#4fc1e8", "#a0d568", "#ed5564"],
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
        <?php if (isset($_GET['page']) && ($_GET['page'] == 'requestmanage' || $_GET['page'] == 'requestme')) { ?>
            <script>
                // request Pie chart
                let requestPieConfig = {
                    series: [<?=$pendingAmount?>,<?=$acceptedAmount?>, <?=$rejectedAmount?>],
                    labels: ["Pending", "Accepted", "Rejected"],
                    colors: ["#57cbeb", "#5ddab4", "#ff7876"],
                    chart: {
                        type: "pie",
                        width: "100%",
                        height: "400px",
                    },
                    legend: {
                        fontSize:"20px",
                        position: "bottom",
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '20px',
                            colors: ['#fff']
                        }
                    },
                }
                var requestPieChart = new ApexCharts(document.querySelector(".request-pie-chart"), requestPieConfig)
                requestPieChart.render();
            </script>

        <?php } ?>
        </body>

        </html>