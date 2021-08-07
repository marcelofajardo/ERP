<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SEO\Mozscape;
use App\SEOAnalytics;
use Carbon\Carbon;
use File;
use App\LinksToPost;

class SEOAnalyticsController extends Controller
{

    protected $url;

    public function __construct()
    {
        $this->middleware('auth');
        $this->url = env('APP_URL_FOR_SEO', 'google.com');
    }

    public function show(){
        $latestEntry = SEOAnalytics::orderBy('created_at','DESC')->first();

        if(empty($latestEntry) || Carbon::now()->diff(Carbon::parse($latestEntry->created_at))->days > 0){
            $data = (object) Mozscape::getSiteDetails($this->url);
            $latestEntry = new SEOAnalytics();
            $latestEntry->domain_authority = $data->domain_authority;
            $latestEntry->linking_authority = $data->linking_authority;
            $latestEntry->inbound_links = $data->inbound_links;
            $latestEntry->ranking_keywords = $data->ranking_keywords ? $data->ranking_keywords : null;
            $latestEntry->save();
        }

        return view('seo.show-analytics', [
            'today' => $latestEntry,
            'data' => SEOAnalytics::orderBy('created_at','DESC')->paginate(20)
        ]);
    }

    public function delete($id){
        $entry = SEOAnalytics::find($id);
        if(!$entry){
            return response()->json(['message' => 'The entry has already been removed or is inaccessible to you!'],400);
        }else{
            $entry->delete();
            return response()->json(['message' => 'The entry has been removed!'],200);
        }
    }

    public function filter(Request $request){
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $latestEntry = SEOAnalytics::orderBy('created_at','DESC')->first();
        if($start_date && $end_date){
            $start_date = Carbon::parse($start_date)->format('Y-m-d h:m:s');
            $end_date = Carbon::parse($end_date)->format('Y-m-d h:m:s');
            $data = SEOAnalytics::whereBetween('created_at', [$start_date, $end_date])->paginate(20);
        }else{
            $data = SEOAnalytics::orderBy('created_at', 'DESC')->paginate(20);
        }
        return view('seo.show-analytics', [
            'today' => $latestEntry,
            'data' => $data,
            'start_date' => Carbon::parse($start_date)->format('d-m-Y'),
            'end_date' => Carbon::parse($end_date)->format('d-m-Y')
        ]);
    }

    public function linksToPost()
    {
        $json = File::get(public_path('uploads/files/luxuryfashionblogs-1.json'));
        $data = json_decode($json);
        foreach ($data as $key => $value) {

            if (LinksToPost::where('article', '=', $value->description)->exists()) {
                break;
            }
            else{

                $domain_link = $value->link;
                $article = $value->description;
                $pieces = parse_url($domain_link);
                $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
                if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
                    $domain_name =  $regs['domain'];
                }else{
                    $domain_name = NULL;
                }

                $shortenize = function( $article ) {
                    return substr( $article, 0, 3 );
                };
                $month_names = array(
                    "january",
                    "february",
                    "march",
                    "april",
                    "may",
                    "june",
                    "july",
                    "august",
                    "september",
                    "october",
                    "november",
                    "december"
                );
                $short_month_names = array_map( $shortenize, $month_names );
                // Define day name
                $day_names = array(
                    "monday",
                    "tuesday",
                    "wednesday",
                    "thursday",
                    "friday",
                    "saturday",
                    "sunday"
                );
                $short_day_names = array_map($shortenize, $day_names );
                // Define ordinal number
                $ordinal_number = ['st', 'nd', 'rd', 'th'];
                $day = "";
                $month = "";
                $year = "";
                // Match dates: 01/01/2012 or 30-12-11 or 1 2 1985
                preg_match( '/([0-9]?[0-9])[\.\-\/ ]+([0-1]?[0-9])[\.\-\/ ]+([0-9]{2,4})/', $article, $matches );
                if ( $matches ) {
                    if ( $matches[1] )
                        $day = $matches[1];
                    if ( $matches[2] )
                        $month = $matches[2];
                    if ( $matches[3] )
                        $year = $matches[3];
                }
                // Match dates: Sunday 1st March 2015; Sunday, 1 March 2015; Sun 1 Mar 2015; Sun-1-March-2015
                preg_match('/(?:(?:' . implode( '|', $day_names ) . '|' . implode( '|', $short_day_names ) . ')[ ,\-_\/]*)?([0-9]?[0-9])[ ,\-_\/]*(?:' . implode( '|', $ordinal_number ) . ')?[ ,\-_\/]*(' . implode( '|', $month_names ) . '|' . implode( '|', $short_month_names ) . ')[ ,\-_\/]+([0-9]{4})/i', $article, $matches );
                if ( $matches ) {
                    if ( empty( $day ) && $matches[1] )
                        $day = $matches[1];
                    if ( empty( $month ) && $matches[2] ) {
                        $month = array_search( strtolower( $matches[2] ),  $short_month_names );
                        if ( ! $month )
                            $month = array_search( strtolower( $matches[2] ),  $month_names );
                        $month = $month + 1;
                    }
                    if ( empty( $year ) && $matches[3] )
                        $year = $matches[3];
                }
                // Match dates: March 1st 2015; March 1 2015; March-1st-2015
                preg_match('/(' . implode( '|', $month_names ) . '|' . implode( '|', $short_month_names ) . ')[ ,\-_\/]*([0-9]?[0-9])[ ,\-_\/]*(?:' . implode( '|', $ordinal_number ) . ')?[ ,\-_\/]+([0-9]{4})/i', $article, $matches );
                if ( $matches ) {
                    if ( empty( $month ) && $matches[1] ) {
                        $month = array_search( strtolower( $matches[1] ),  $short_month_names );
                        if ( ! $month )
                            $month = array_search( strtolower( $matches[1] ),  $month_names );
                        $month = $month + 1;
                    }
                    if ( empty( $day ) && $matches[2] )
                        $day = $matches[2];
                    if ( empty( $year ) && $matches[3] )
                        $year = $matches[3];
                }
                // Match month name:
                if ( empty( $month ) ) {
                    preg_match( '/(' . implode( '|', $month_names ) . ')/i', $article, $matches_month_word );
                    if ( $matches_month_word && $matches_month_word[1] )
                        $month = array_search( strtolower( $matches_month_word[1] ),  $month_names );
                    // Match short month names
                    if ( empty( $month ) ) {
                        preg_match( '/(' . implode( '|', $short_month_names ) . ')/i', $article, $matches_month_word );
                        if ( $matches_month_word && $matches_month_word[1] )
                            $month = array_search( strtolower( $matches_month_word[1] ),  $short_month_names );
                    }
                    if(is_int($month)){
                        $month = $month + 1;
                    }

                }
                // Match 5th 1st day:
                if ( empty( $day ) ) {
                    preg_match( '/([0-9]?[0-9])(' . implode( '|', $ordinal_number ) . ')/', $article, $matches_day );
                    if ( $matches_day && $matches_day[1] )
                        $day = $matches_day[1];
                }
                // Match Year if not already setted:
                if ( empty( $year ) ) {
                    preg_match( '/[0-9]{4}/', $article, $matches_year );
                    if ( $matches_year && $matches_year[0] )
                        $year = $matches_year[0];
                }
                if ( ! empty ( $day ) && ! empty ( $month ) && empty( $year ) ) {
                    preg_match( '/[0-9]{2}/', $article, $matches_year );
                    if ( $matches_year && $matches_year[0] )
                        $year = $matches_year[0];
                }
                // Day leading 0
                if ( 1 == strlen( $day ) )
                    $day = '0' . $day;
                // Month leading 0
                if ( 1 == strlen( $month ) )
                    $month = '0' . $month;
                // Check year:
                if ( 2 == strlen( $year ) && $year > 20 )
                    $year = '19' . $year;
                else if ( 2 == strlen( $year ) && $year < 20 )
                    $year = '20' . $year;
                $date = $year . $month . $day;

                // Return false if nothing found:
                if ( empty( $year ) && empty( $month ) && empty( $day ) ){
                    $date = NULL;
                }

                $linksToPost = new LinksToPost;
                $linksToPost->name = $domain_name;
                $linksToPost->date_scrapped = $date;
                $linksToPost->article = $article;
                $linksToPost->link = $domain_link;
                $linksToPost->save();

            }

        }
        //
        return redirect()->back();
    }
}