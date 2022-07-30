<div class="mb-4" x-data="data()" x-init="mounted()">

    @section('header')
        <x-page-title title="Estadísticas del repositorio"
            description="Este módulo permite ver la evaluación final del repositorio de acuerdo con las respuestas del usuario.">
        </x-page-title>
    @endsection

    <div class="row">
        <div class="mb-3 col-12 col-lg-12">
            <div class="border-0 shadow card mb-3">
                <div class="card-body" id="bubbleChartContainer">
                    <div id="stacked_bar-chart-container">
                        <canvas id="stacked_bar-chart"></canvas>
                    </div>
                </div>
            </div>
            <div class="border-0 shadow card">
                <div class="card-body" id="bubbleChartContainer">
                    <div id="stacked_radar-chart-container">
                        <canvas id="stacked_radar-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="mb-3 col-12 col-lg-6">
            <div class="border-0 shadow card">
                <div class="card-body">
                    <canvas id="repositoryQualification"></canvas>
                </div>
                <div class="card-footer d-flex justify-content-center">
                    <div class="mt-0 widget-numbers fsize-3">
                        <span>{{ $repository->qualification }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3 col-12 col-lg-3">
            <div class="border-0 shadow card">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <div class="my-auto mb-2 mr-2 badge badge-danger">&nbsp;</div>
                        <div> 0% a 40% (insuficiente) </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <div class="my-auto mb-2 mr-2 badge badge-warning">&nbsp;</div>
                        <div> 40% a 75% (Grado medio/ntermedio) </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <div class="my-auto mb-2 mr-2 badge badge-success">&nbsp;</div>
                        <div> 75% a 100% (excelente) </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($categories as $category)
            <div class="mb-3 col-12 col-lg-4">
                <div class="border-0 shadow card">
                    <div class="card-body">
                        <canvas category-id="{{ $category->id }}"></canvas>
                    </div>
                    <div class="card-footer d-flex justify-content-center">
                        <div class="mt-0 widget-numbers fsize-3">
                            <span percentage-id="{{ $category->id }}">0.00%</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function data() {
            return {

                repository: @json($repository),
                categories: @json($categories),
                subcategories: @json($subcategories),

                mounted() {

                    this.categories.forEach(category => {

                        category.percentage = CategoryService.punctuationPercentage(category)

                        if (category.percentage <= 40) {
                            color = '#ff6384'
                        } else if (category.percentage > 40 && category.percentage < 75) {
                            color = '#f7b924'
                        } else {
                            color = '#3ac47d'
                        }


                        percentageSpan = document.querySelector(`span[percentage-id="${category.id}"]`);
                        percentageSpan.innerText = `${category.percentage.toFixed(2)}%`

                        // Get category canvas
                        ctx = document.querySelector(`canvas[category-id="${category.id}"]`);
                        var myChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: [category.short_name],
                                datasets: [{
                                    data: [category.percentage, 100 -
                                        category.percentage
                                    ],
                                    backgroundColor: [
                                        color,
                                        'rgb(180, 190, 180)',
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                rotation: Math.PI,
                                circumference: Math.PI,
                                tooltips: {
                                    callbacks: {
                                        label: (tooltipItem, data) => {
                                            return tooltipItem.index == 0 ? category.short_name +
                                                ":" + category.percentage + "%" : ""
                                        }
                                    },
                                },
                            }
                        });
                    });

                    if (this.repository.qualification <= 40) {
                        color = '#ff6384'
                    } else if (this.repository.qualification > 40 && this.repository.qualification < 75) {
                        color = '#f7b924'
                    } else {
                        color = '#3ac47d'
                    }

                    ctx = document.getElementById('repositoryQualification');

                    var myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Calificación general', 'nada'],
                            datasets: [{
                                data: [parseFloat(this.repository.qualification) + 100, 100 -
                                    parseFloat(this.repository.qualification)
                                ],
                                backgroundColor: [
                                    color,
                                    'rgba(220, 220, 220, 220)',
                                ],

                                borderColor: [
                                    color,
                                    'rgba(220, 220, 220, 220)',
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            rotation: 1 * Math.PI,
                            circumference: 1 * Math.PI,
                            tooltips: {
                                callbacks: {
                                    label: (tooltipItem, data) => {
                                        return tooltipItem.index == 0 ? "Calificación:" + this.repository
                                            .qualification + "%" : "vacio"
                                    }
                                },
                            },
                        }
                    });

                    this.setStackedBarChart()
                    this.setStackedRadarChart()

                    // LINEAR CHART FOR ALL TACOMETERS

                },

                setStackedBarChart() {

                    const categoryCollection = new CategoryCollection(this.categories)

                    requiredSubcategory = this.subcategories[0]
                    recomendedSubcategory = this.subcategories[1]

                    document.getElementById('stacked_bar-chart-container').innerHTML =
                        `<canvas id="stacked_bar-chart"><canvas>`;

                    new Chart(document.getElementById("stacked_bar-chart"), {

                        type: 'bar',
                        data: {
                            labels: categoryCollection.pluckShortNames(),
                            datasets: [{
                                    label: "Requisito",
                                    backgroundColor: 'rgb(200, 40, 20)',
                                    data: categoryCollection.pluckPunctuationPercentagesOfSubcategory(
                                        requiredSubcategory)
                                },
                                {
                                    label: "Recomendación ",
                                    backgroundColor: 'rgb(100, 220, 200)',
                                    data: categoryCollection.pluckPunctuationPercentagesOfSubcategory(
                                        recomendedSubcategory)
                                }
                            ]
                        },
                        options: {
                            tooltips: {
                                displayColors: true,
                                callbacks: {
                                    mode: 'x',
                                },
                            },
                            scales: {
                                xAxes: [{
                                    stacked: false,
                                    gridLines: {
                                        display: false,
                                    }
                                }],
                                yAxes: [{
                                    stacked: false,
                                    ticks: {
                                        beginAtZero: true,
                                        min: 0,
                                        max: 100,
                                        stepSize: 10,
                                    },
                                    type: 'linear',
                                }]
                            },
                            responsive: true,
                            maintainAspectRatio: true,
                            legend: {
                                position: 'right'
                            },
                        }
                    });
                },


                setStackedRadarChart() {

                    const categoryCollection = new CategoryCollection(this.categories)

                    document.getElementById('stacked_radar-chart-container').innerHTML =
                        `<canvas id="stacked_radar-chart"><canvas>`;

                    new Chart(document.getElementById("stacked_radar-chart"), {

                        type: 'radar',
                        data: {
                            labels: categoryCollection.pluckShortNames(),
                            datasets: [{
                                label: "Requisito",
                                backgroundColor: 'rgb(200, 40, 20, 0.5)',
                                data: categoryCollection.pluckPunctuationPercentagesOfSubcategory(this.subcategories[0])
                            }, {
                                label: "Recomendación",
                                backgroundColor: 'rgb(100, 220, 200, 0.5)',
                                data: categoryCollection.pluckPunctuationPercentagesOfSubcategory(this.subcategories[1])
                            }, ]
                        },
                        options: {
                            tooltips: {
                                displayColors: true,
                            },
                            scale: {
                                ticks: {
                                    beginAtZero: true,
                                    min: 0,
                                    max: 100,
                                    stepSize: 10,
                                },
                            },
                            responsive: true,
                            maintainAspectRatio: true,
                            legend: {
                                position: 'right'
                            },
                        }
                    });
                },



            }
        }

    </script>


    <script>
        class CategoryCollection {

            constructor(categories) {
                this.categories = categories
            }

            pluckShortNames() {
                return this.categories.map(category => {
                    return category.short_name;
                })
            }

            pluckPunctuationPercentages() {
                return _.map(this.categories, (category) => {
                    return CategoryService.punctuationPercentage(category)
                })
            }

            pluckPunctuationPercentagesOfSubcategory(subcategory) {
                return _.map(this.categories, (category) => {
                    return CategoryService.punctuationPercentageOfSubcategory(category, subcategory)
                })
            }



        }

        class CategoryService {

            /*
             * get the total punctuation of the categorie's answers
             * 
             */
            static punctuation(category) {
                const punctuations = _.map(category.questions, function(question) {
                    return question.answer.choice ? parseFloat(question.answer.choice
                        .punctuation) : 0
                })
                return _.sum(punctuations)
            }

            /**
             */
            static punctuationOfSubcategory(category, subcategory) {
                let questions = _.filter(category.questions, question => question.subcategory_id == subcategory.id)

                const punctuations = _.map(questions, function(question) {
                    return question.answer.choice ? parseFloat(question.answer.choice
                        .punctuation) : 0
                })
                return _.sum(punctuations)
            }


            /*
             * calculate the max punctuation of the category
             *
             */
            static maxPosiblePunctuation(category) {
                const max_punctuations = _.map(category.questions, function(question) {
                    return parseFloat(question.max_punctuation)
                })
                return _.sum(max_punctuations)
            }

            /*
             * calculate the max punctuation of the specific subcategory
             *
             */
            static maxPosiblePunctuationOfSubcategory(category, subcategory) {
                let questions = _.filter(category.questions, question => question.subcategory_id == subcategory.id)

                const max_punctuations = _.map(questions, function(question) {
                    return parseFloat(question.max_punctuation)
                })
                return _.sum(max_punctuations)
            }

            /**
             * calculate the percentage in a scale from 0 - 100
             *
             */
            static punctuationPercentage(category) {
                return CategoryService.punctuation(category) / CategoryService.maxPosiblePunctuation(category) * 100
            }

            /**
             * calculate the percentage in a scale from 0 - 100
             *
             */
            static punctuationPercentageOfSubcategory(category, subcategory) {
                return CategoryService.punctuationOfSubcategory(category, subcategory) / CategoryService
                    .maxPosiblePunctuationOfSubcategory(category, subcategory) * 100
            }


            /**
             * calculate the percentage of specific subcategory in a scale from 0 - 100
             */
            static punctuationPercentageForSubcategory(subcategory) {
                return CategoryService.punctuation(category) / CategoryService.maxPosiblePunctuation(category) * 100
            }
        }

        class SubcategoryService {

            /*
             * calculate the max punctuation of the subcategory
             *
             */
            static maxPosiblePunctuation(subcategory) {

            }

        }

    </script>

</div>
