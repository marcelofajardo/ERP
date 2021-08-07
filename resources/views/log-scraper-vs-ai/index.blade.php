<html>
<head>
    <title>Scraper vs AI Results</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>

<body>

<div class="container-fluid">
    @foreach($results as $key => $item)
        @if ($item)
            @php
                $resultScraper = json_decode($item->result_scraper);
                $resultAi = json_decode($item->result_ai);
            @endphp
            <div class="row">
                <div class="col-sm-12">
                    <h6>{{ date('d-m-Y H:i', strtotime($item->created_at)) }} Scraper vs {{ $item->ai_name }}</h6>
                </div>
            </div>
            @if ( !isset($i) )
                @php
                    $i = 1;

                    if ( isset($_GET['opener'])) {
                        $openerField = "<input type='hidden' name='opener' value='" . $_GET['opener'] . "' />\n";
                    } else {
                        $openerField = '';
                    }
                @endphp
                @if (session('alert'))
                    <div class="alert alert-danger">
                        {{ session('alert') }}
                    </div>
                @endif
                <form method="post" action="{{ action('\App\Http\Controllers\logScraperVsAiController@index', $item->product_id) }}" target="_top">
                    @csrf
                    {{ method_field('POST')}}
                    {!! $openerField !!}
                    <div class="flex-row">
                        <div class="col-sm-6">
                            <h3>Category</h3>
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="input-group">
                                        <input type="radio" name="category" value="{{ $resultScraper->category }}">
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <label>{{ ucwords(strtolower($resultScraper->category)) }} (scraper)</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-1">
                                    <input type="radio" name="category" value="{{ \App\LogScraperVsAi::getCategoryIdByKeyword( $resultAi->category, $resultAi->gender, $genderScraper ) }}" <?php echo empty( $resultAi->gender ) && empty( $genderScraper ) ? 'checked' : ''; ?>>
                                </div>
                                <div class="col-sm-10">
                                    <label> {{ ucwords(strtolower($resultAi->category)) }} (AI)</label>
                                </div>
                            </div>
                            @if(is_array($keywords))
                                @foreach( $keywords as $keyword=>$count)
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <input type="radio" name="category" value="{{ \App\LogScraperVsAi::getCategoryIdByKeyword( $keyword, $resultAi->gender, $genderScraper ) }}">
                                        </div>
                                        <div class="col-sm-10">
                                            <label>{{ ucwords(strtolower($keyword)) }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row">
                                <div class="col-sm-1">
                                    <input type="radio" name="category" value="dropdown" <?php echo empty( $resultAi->gender ) && empty( $genderScraper ) ? '' : 'checked'; ?>>
                                </div>
                                <div class="sm-col-11">
                                    <?php
                                    // Set category
                                    $categoryDropDown = \App\Category::attr( [ 'name' => 'category_dropdown', 'class' => 'form-control', 'id' => 'product-category', 'style' => 'max-width: 50%;' ] )
                                        ->selected( \App\LogScraperVsAi::getCategoryIdByKeyword( $resultAi->category, $resultAi->gender, $genderScraper ) )
                                        ->renderAsDropdown();

                                    $category = $resultAi->category;
                                    $categories = \App\Category::select( [ 'id', 'title' ] )->orderBy( 'title', 'asc' )->get();

                                    echo $categoryDropDown;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <h3>Color</h3>
                            <div class="row">
                                @if ( !empty($resultScraper->color) )
                                    <div class="col-sm-1">
                                        <div class="input-group">
                                            <input type="radio" name="color" value="{{ $resultScraper->color }}">
                                        </div>
                                    </div>
                                    <div class="sm-col-11">
                                        <label>
                                            {{ ucwords(strtolower($resultScraper->color)) }} (scraper)
                                        </label>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                @if ( !empty($resultAi->color) )
                                    <div class="col-sm-1">
                                        <div class="input-group">
                                            <input type="radio" name="color" value="{{ $resultAi->color }}">
                                        </div>
                                    </div>
                                    <div class="sm-col-11">
                                        <label>
                                            {{ ucwords(strtolower($resultAi->color)) }} (AI)
                                        </label>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-sm-1">
                                    <input type="radio" name="color" value="dropdown" checked>
                                </div>
                                <div class="sm-col-11">
                                    <?php
                                    $color = $resultAi->color;
                                    $colors = new \App\Colors();
                                    echo Form::select( 'color_dropdown', $colors->all(), ucwords( $color ), [ 'placeholder' => 'Select a color', 'class' => 'form-control', 'style' => 'max-width: 50%;' ] );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-primary" style='margin: 15px;' value="Submit and edit attributes">
                        </div>
                    </div>
                </form>
            @endif
            <div class="row">
                <div class="col-sm-12">
                    @php
                        $images = json_decode($item->media_input);
                    @endphp
                    @foreach( $images as $image )
                        <img src="{{ $image }}" style="height: 200px; width: auto;">
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width: 50%;" scope="col"><h4>Scraper</h4></th>
                            <th style="width: 50%;" scope="col"><h4>AI</h4></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="m-5">
                                @foreach( $resultScraper as $resultKey=>$resultValue)
                                    {{ $resultKey }}: <b>{{ $resultValue }}</b><br/>
                                @endforeach
                            </td>
                            <td class="m-5">
                                @foreach( $resultAi as $resultKey=>$resultValue)
                                    {{ $resultKey }}: <b>{{ $resultValue }}</b><br/>
                                @endforeach
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
</div>

</body>

</html>