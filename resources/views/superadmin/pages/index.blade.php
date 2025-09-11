@extends('superadmin.dashboard')

@section('content')

<style>
    .main {
        display: flex;
        justify-content: center;
        min-height: 100vh;
    }

    .dashboard-wrapper {
        padding: 20px
    }

    .dashboard-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 12px rgb(9 9 9 / 19%);
        /* max-width: 1000px;
        width: 100%; */
        margin: auto;
        margin-top: 2px;
        margin-left: 20px;
        margin-right: 20px;
    }

    .dashboard-header {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 12px;
        margin-bottom: 25px;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }

    .search input {
        border: 1px solid #ced4da;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 13px;
        width: 100%;
    }

    .cardBox {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .card {
        background-color: #f9f9f9;
        flex: 1 1 30%;
        padding: 20px;
        border-radius: 10px;
        min-width: 250px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .cardName {
        font-weight: 600;
        font-size: 14px;
        color: #3b68b2;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .numbers {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
    }

    .graph-container {
        width: 100%;
        height: 250px;
    }

    canvas {
        width: 100% !important;
        height: auto !important;
    }

    @media (max-width: 992px) {
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            overflow-y: auto !important; /* Enables vertical scroll */
        }

        .main {
            min-height: auto;
            padding-bottom: 50px;
            overflow-y: auto !important; /* Enables scrolling within main */
        }

        .topbar {
            gap: 10px;
        }

        .search.w-50 {
            width: 100% !important;
        }

        .cardBox {
            flex-direction: column;
        }

        .card {
            flex: 1 1 100%;
            min-width: unset;
        }

        .graph-container {
            width: 100%;
            max-height: 250px;
            overflow-x: auto;
        }
    }

    @media (max-width: 576px) {
        .dashboard-header {
            font-size: 18px;
            text-align: center;
        }

        .dashboard-card {
            padding: 15px;
        }

        .cardName {
            font-size: 13px;
        }

        .numbers {
            font-size: 20px;
        }

        .graph-container {
            height: auto;
            overflow-x: auto !important; /* Let graphs scroll if they overflow */
        }

        .dashboard-card {
            padding: 10px;
            margin-left: 10px;
            margin-right: 10px;
        }

        .dashboard-header {
            font-size: 16px;
        }

        .cardName {
            font-size: 12px;
        }

        .numbers {
            font-size: 18px;
        }

        .main {
            position: static !important;
            width: 100% !important;
            height: auto !important;
            overflow-y: auto !important;
            padding-bottom: 80px; /* so bottom content isn’t cut off */
        }

        body, html {
            overflow-x: hidden;
        }

    }




</style>

<div class="main">
    <div class="container-fluid">
        <div class="dashboard-wrapper">
            <div class="dashboard-card">
                <h1 class="dashboard-header">Admin Dashboard</h1>

                <div class="topbar">
                    <div class="search w-50">
                        <input type="text" placeholder="Search here">
                    </div>
                    <div class="search w-50">
                        <input type="text" placeholder="Sort by date">
                    </div>
                </div>

                <!-- ======================= Cards ================== -->
                <div class="cardBox">
                    <div class="card">
                        <div class="cardName">
                            <ion-icon name="people-outline"></ion-icon> TOTAL REGISTRATION
                        </div>
                        <div class="numbers">{{ $users->count() }}</div>
                    </div>

                    <div class="card">
                        <div class="cardName">
                            <ion-icon name="person-outline"></ion-icon> USER'S REGISTRATION
                        </div>
                        <div class="graph-container mt-3">
                            <canvas id="registrationChart"></canvas>
                        </div>
                    </div>

                    <div class="card">
                        <div class="cardName">
                            <ion-icon name="cash-outline"></ion-icon> TOTAL REVENUE
                        </div>
                        <div class="numbers">$7,842</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var registrations = @json($registrations);
    var months = registrations.map(function(item) {
        var monthNames = ["January", "February", "March", "April", "May", "June", "July",
                          "August", "September", "October", "November", "December"];
        return monthNames[item.month - 1];
    });

    var counts = registrations.map(function(item) {
        return item.count;
    });

    if (typeof Chart !== 'undefined') {
        var ctx = document.getElementById('registrationChart').getContext('2d');
        var registrationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Registrations',
                    data: counts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        console.error('Chart.js is not loaded properly.');
    }
</script>
@endsection
