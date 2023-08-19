<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Monitoring App') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Ajouter un nouveau module
                    </a>
                    <table id="myTable" class="table wrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Serie</th>
                                <th>Nom</th>
                                <th>Dimension</th>
                                <th>Etat</th>
                                <th>Nb données</th>
                                <th>Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $module->serie }}</td>
                                    <td>{{ $module->name }} </td>
                                    <td>{{ $module->dimensions }} </td>
                                    <td>{{ $module->etat }} </td>
                                    <td>{{ $module->nbOfDataSent() }}</td>
                                    <td>
                                        </button>
                                        <a class="btn btn-danger fa fa-trash"
                                            href="{{ route('suppr-module', ['id' => $module->id]) }}"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="chBar"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal form -->
    <div class="modal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajout d'un nouveau module</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('ajout-module') }}">
                        @csrf

                        <!-- serie -->
                        <div>
                            <x-input-label for="serie" :value="__('Serie')" />
                            <x-text-input id="serie" class="block mt-1 w-full" type="text" name="serie"
                                required />
                        </div>

                        <!-- Nom -->
                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Nom')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                required />
                        </div>

                        <!-- Dimension -->
                        <div class="mt-4">
                            <x-input-label for="dimensions" :value="__('Dimensions')" />

                            <x-text-input id="dimensions" class="block mt-1 w-full" type="text" name="dimensions"
                                required />
                        </div>

                        <!-- Etat -->
                        <div class="mt-4">
                            <x-input-label for="etat" :value="__('Etat')" />

                            <select id="etat"
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full form-select"
                                type="text" name="etat" required>
                                <option Value="ACTIF">ACTIF</option>
                                <option Value="INACTIF">INACTIF</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                            <input class="btn btn-success bg-success" type="submit" value="Valider" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--            -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
    <script>
        let table = new DataTable('#myTable');
        const colors = ['#007bff', '#28a745'];
        /*
            - creation des labels à partir de nom des modules
            - extraction des 2 dernières temperatures pour chaque module
            - extraction des 2 dernières pressions pour chaque module
        */
        const modules = @json($modules);
        const dataStreams = @json($dataStreams);
        const temperatures = {
            prev: [],
            curr: [],
        };

        const labels = [];
        for (let i = 0; i < modules.length; i++) {
            var tmp = dataStreams.filter(data => {
                return (data.module === modules[i].id);
            });
            const curr = tmp[0] ? parseInt(tmp[0].temperature) : 0;
            const prev = tmp[1] ? parseInt(tmp[1].temperature) : 0;
            temperatures.curr.push(curr);
            temperatures.prev.push(prev);
            labels.push(modules[i].name);
            console.log(tmp);
        }
        console.log(temperatures.prev);
        console.log(temperatures.curr);
        const chBar = document.querySelector("#chBar");
        if (chBar) {
            new Chart(chBar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Température precedente',
                            data: temperatures.prev,
                            backgroundColor: colors[0]
                        },
                        {
                            label: 'Température actuelle',
                            data: temperatures.curr,
                            backgroundColor: colors[1]
                        }
                    ]
                },
                options: {
                    legend: {
                        display: true,
                    },
                    scales: {
                        xAxes: [{
                            barPercentage: 0.4,
                            categoryPercentage: 0.5,
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    </script>
    <script src="{{ asset('data-script.js') }}"></script>
</x-app-layout>
