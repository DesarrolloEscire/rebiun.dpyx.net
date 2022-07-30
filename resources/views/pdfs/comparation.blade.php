<style>
    * {
        font-family: Helvetica;
    }

    table {
        border-collapse: collapse;
    }

    td {
        padding: 3px;
    }

    table,
    td {
        border: 1px solid black;
    }

    .page_break {
        page-break-before: always;
    }

    body {
        margin-left: 50px;
        margin-top: 120px;
        margin-bottom: 55px;
    }

    .header {
        position: fixed;
        top: -10px;
        left: 0px;
        right: 0px;
        margin-bottom: 10em;
        /* height: 50px; */

        /** Extra personal styles **/
        /* background-color: #03a9f4; */
        /* color: white; */
        text-align: center;
        /* line-height: 35px; */
    }

    thead {
        display: table-row-group;
    }

    /* footer {
        position: fixed;
        bottom: 40px;
        text-align: center;
        font-size: 11px;
        font-weight: bold;
    } */

    footer {
        position: fixed;
        bottom: -20px;
        left: 0px;
        right: 0px;
        height: 50px;

        /** Extra personal styles **/
        /* background-color: #03a9f4; */
        /* color: white; */
        text-align: center;
        line-height: 35px;
        font-size: 11px;
        font-weight: bold;
    }

    div {
        font-size: 14px;
        width: 90%;
    }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="header">
 {{--   <img width="100%"
        src="data:image/png;base64,' . {{ base64_encode( file_get_contents( url('images/default/evaluation/banner_pdf.jpg') ) ) }} " /><br />--}}
</div>

<footer>
    {{env("INSTITUTE_ADDRESS", "© REBIUN | RED DE BIBLIOTECAS UNIVERSITARIAS 2017")}}<br />
    Correo: {{env("INSTITUTE_MAIL", "rebiun@crue.org")}}<br />
</footer>

<body>



    @foreach ($categories as $category)
    <div>
        <h4>
            {{$category->name}}
        </h4>

        <table width="110%" class="table table-striped table-sm" style="table-layout: fixed">
            @foreach ($subcategories as $subcategory)

            <thead>
                <tr>
                    <td width="42%" align="left"><b>{{$subcategory->name}}</b></td>
                    <td width="16%" align="center"><b>Resultado</b></td>
                    @php
                    $evaluators_ids=[];
                    @endphp
                    @foreach ($repository->evaluators as $evaluator)
                    <td width="42%" align="left"><b>Observaciones de <br> {{$evaluator->evaluator_name}}</b></td>
                    @php
                    $evaluators_ids[] = $evaluator->id;
                    @endphp
                    @endforeach


                  {{--  <td width="42%" align="left"><b>Observaciones de <br> {{$repository->name}}</b></td>--}}
                </tr>
            </thead>
            <tbody>
                @foreach ($subcategory->questions->where('category_id',$category->id)->all() as $question)
                <tr>
                    <td style="word-wrap: break-word">{{$question->description}}</td>
                    <td style="word-wrap: break-word">
                        {{$question->answers->first() && $question->answers->first()->choice ? $question->answers->first()->choice->description : 'N/A'}}
                    </td>

                    {{--@foreach ($question->answers->first()->observations as $observation)--}}
                    @foreach ($evaluators_ids as $id)
                    @php
                    $description=null;
                    foreach ($question->answers->first()->observations as $observation){
                        if($id == $observation->evaluator_id){
                            $description = $observation->description;
                            break;
                        }

                    }
                    @endphp


                    <td style="word-wrap: break-word">
                        {{$question->answers->first() && $description ? $description : ''}}
                    </td>


                    @endforeach


                </tr>
                @endforeach
            </tbody>

            @endforeach
        </table>

    </div>
    @endforeach



</body>
