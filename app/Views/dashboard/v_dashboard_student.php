<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <!-- Pie Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="gradeChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bar Chart: Credits taken vs. credits required -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="creditChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Line Chart: GPA per Semester -->
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="gpaChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Data dari controller
    
    const creditComparison = <?= $creditComparison ?>;
    const creditsByGrade = <?= $creditsByGrade ?>;


    //JS Pie Chart
    const gradeChart = new Chart(
        document.getElementById('gradeChart'),
        {
            type: 'pie',
            //Change the data here
            data: creditsByGrade,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Credit Distribution by Grade'
                    },
                    legend: {
                        position: 'right'
                    }
                }
            }
        }
    );

    //JS Bar Chart
    const creditChart = new Chart(
        document.getElementById('creditChart'),
        {
            type: 'bar',
            //Change the data here
            data: creditComparison,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Credits'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Semester'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Credits Taken vs. Credits Required by Semester'
                    }
                }
            }
        }
    );

    //JS Line Chart
    // const gpaChart = new Chart(
    //     document.getElementById('gpaChart'),
    //     {
    //         type: 'line',
    //         //Change the data here
    //         data: gpaData,
    //         options: {
    //             responsive: true,
    //             maintainAspectRatio: false,
    //             scales: {
    //                 y: {
    //                     min: 0,
    //                     max: 4,
    //                     title: {
    //                         display: true,
    //                         text: 'GPA'
    //                     }
    //                 },
    //                 x: {
    //                     title: {
    //                         display: true,
    //                         text: 'Semester'
    //                     }
    //                 }
    //             },
    //             plugins: {
    //                 title: {
    //                     display: true,
    //                     text: 'Academic Progress (GPA per Semester)'
    //                 },
    //                 tooltip: {
    //                     callbacks: {
    //                         label: function (context) {
    //                             return `GPA: ${context.raw}`;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // );

</script>
<?= $this->endSection() ?>