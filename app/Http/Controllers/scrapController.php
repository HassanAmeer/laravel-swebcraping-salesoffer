<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Goutte\Client;

class scrapController extends Controller
{
    public function scrapf (){
      
  /* ---------------- 2nd ----------------------*/
    $client = new Client();
    $url  = 'https://whatsonsale.com.pk/';
    $vpage = $client->Request('GET', $url);
    $catgandstorespath = $client->Request('GET', 'https://whatsonsale.com.pk/categories');
    
    // class="thread clearfix"
    // echo $vpage->filter(selector:'.img-responsive')->text();
    // print_r($vpage->filter(selector:'.view-content'));
    
    $featuredsaleslist = [];

    $vpage->filter('div.thread')->each(function ($elements) use (&$featuredsaleslist) {
        $title = $elements->filter('strong.thread-title a')->text();
        $pos = strpos($title, '!');
        // $pos2 = strpos($title, '%');
        if ($pos !== false) {
          $saleName = substr($title, 0, $pos);
          $offer = substr($title, $pos+1, 10);  }
        $description = $elements->filter('div.thread-description')->text();
        $imageSrc = $elements->filter('div.field-name-field-deal-image img')->attr('src');
        $authorName = $elements->filter('ul.subtext li a.username')->text();
        $postDate = $elements->filter('ul.subtext li span.post-date')->text();
        $votes = $elements->filter('ul.subtext li b')->text();
        $detailslink = $elements->filter('div.field-name-field-deal-image a')->attr('href');

        $featuredsaleslist[] = [
            'title' => $title,
            'saleName' => $saleName,
            'offer' => $offer,
            'description' => $description,
            'imageSrc' => $imageSrc,
            'authorName' => $authorName,
            'postDate' => $postDate,
            'votes' => $votes,
            'detailslink' => $detailslink,
        ];
    });

      //---------------------------------------------------------------------//
 // old 
      $catglist = [];
      $catgandstorespath->filter('div.region-content section#block-system-main div.view-content div')->each(function ($elements) use (&$catglist) {
      $catglink = $elements->filter('a')->attr('href');
      $catgname = $elements->filter('a')->text(); 
      $catglist []= [
        'catglink' => $catglink,
        'catgname' => $catgname,
      ];
    });




    $popularbrandslist = [];
    $catgandstorespath->filter('section#block-views-stores-block-1 div.view-id-stores div.view-content div.views-row ')->each(function ($elements) use (&$popularbrandslist) {
        $brandsimage = $elements->filter('a span.image img.img-responsive')->attr('src');
        $brandsname = $elements->filter('a span.name')->text();
        $brandslink = $elements->filter('a')->attr('href');

        $popularbrandslist[] = [
            'brandsname' => $brandsname,
            'brandslink' => $brandslink,
            'brandsimage' => $brandsimage,
        ];
    });


      //---------------------------------------------------------------------//
      return view('scrap', compact(['featuredsaleslist', 'popularbrandslist', 'catglist']));
      //---------------------------------------------------------------------//

      // echo "<pre>";
      // print_r($catglist);

    }

    ///////////////////////////// details page data scrap start here
    ///////////////////////////// details page data scrap start here
    ///////////////////////////// details page data scrap start here
    ///////////////////////////// details page data scrap start here
    ///////////////////////////// details page data scrap start here
    public function detailspagef($id)
    {  
        $client = new Client();
        $url = "https://whatsonsale.com.pk/$id"; 
        $vpage = $client->Request('GET', $url);

        $imagesrc = $vpage->filter('div.pull-right img.img-responsive')->attr('src');
        $startdate = $vpage->filter('span.date-display-single')->text();
        $storeimg = $vpage->filter('a.store-img img.img-responsive')->attr('src');
        $storetitle = $vpage->filter('div.store-info h2')->text();
        $storevmoretext = $vpage->filter('div.store-info a')->text();
        $storevmorelink = $vpage->filter('div.store-info a')->attr('href');
        $details = $vpage->filter('div.description p')->text();
        $filtertitle = $vpage->filter('h1.page-header')->text();
        $pos = strpos($filtertitle, '!');
        // $pos2 = strpos($title, '%');
        if ($pos !== false) { $saleName = substr($filtertitle, 0, $pos); $offer = substr($filtertitle, $pos+1, 8); }

        $alsolikelist = [];
        $vpage->filter('section#block-views-deal-blocks-block-8 div.view-id-deal_blocks div.view-content div.views-row')->each(function ($elements) use (&$alsolikelist) {
          $liketitle = $elements->filter('div.views-field-title span.field-content a')->text(); 
          $likelink = $elements->filter('div.views-field-title span.field-content a')->attr('href');
        $alsolikelist []= [
          'liketitle' => $liketitle,
          'likelink' => $likelink,
        ];});
        
        $popularlist = [];
        $vpage->filter('section#block-views-deal-blocks-block div.view-id-deal_blocks div.view-content div.views-row')->each(function ($elements) use (&$popularlist) {
        $populartitle = $elements->filter('div.views-field-title span.field-content a')->text(); 
        $popularlink = $elements->filter('div.views-field-title span.field-content a')->attr('href');
        $popularlist []= [
          'populartitle' => $populartitle,
          'popularlink' => $popularlink,
        ];});
      
          // print_r( $imagesrc.'||||'.$startdate.'||||'.$storeimg.'||||'.$storetitle.'||||'.$storevmoretext.'||||'.$storevmorelink.'||||'.$details.'||||'.$saleName.'||||'.$offer.'||||');      // print_r($alsolikelist);
       
        return view('detailspage', compact(['imagesrc','startdate','storeimg','storetitle','storevmoretext','storevmorelink','details','saleName','offer','popularlist','alsolikelist'])); }

        //////////////////// start view all sales of brand 
        //////////////////// start view all sales of brand 
        //////////////////// start view all sales of brand 
        //////////////////// start view all sales of brand 
    public function vallsaleofbrandsf($id)
    {  
        $client = new Client();
        $url = "https://whatsonsale.com.pk/stores/$id"; 
        $vpage = $client->Request('GET', $url);

        $brandname = $id;
        $brandimg = $vpage->filter('div.bg-store div.pull-left div.store-img img.img-responsive')->attr('src');
        $brandtitle = $vpage->filter('div.bg-store div.store-info h2')->text();
        $brandlink = $vpage->filter('div.bg-store div.store-info a')->text();

        $saleslist = [];
    $vpage->filter('div.thread')->each(function ($elements) use (&$saleslist) {
        $title = $elements->filter('div.thread strong.thread-title a')->text();
        $pos = strpos($title, '!');
        // $pos2 = strpos($title, '%');
        // if ($pos !== false) {
        //   $saleName = substr($title, 0, $pos);
        //   $offer = substr($title, $pos+1, 10);  }
        $description = $elements->filter('div.thread-description p')->text();
        $imageSrc = $elements->filter('div.field-name-field-deal-image img')->attr('src');
        // $authorName = $elements->filter('ul.subtext li a.username')->text();
        // $expirydate = $elements->filter('span.date-display-single')->text();
        // $votes = $elements->filter('ul.subtext li b')->text();
        $postDate = $elements->filter('ul.subtext li span.post-date')->text();
        $detailslink = $elements->filter('div.field-name-field-deal-image a')->attr('href');
        $saleslist[] = [
            'title' => $title,
            // 'saleName' => $saleName,
            // 'offer' => $offer,
            'description' => $description,
            'imageSrc' => $imageSrc,
            // 'authorName' => $authorName,
            // 'expirydate' => $expirydate,
            // 'votes' => $votes,
            'postDate' => $postDate,
            'detailslink' => $detailslink,
        ];
    });

    $popularlist = [];
        $vpage->filter('div.popular-stores div.view-content div.views-row')->each(function ($elements) use (&$popularlist) {
        //  print_r( $elements->text());
          $popularlogo = $elements->filter('div.views-field-field-logo img.img-responsive')->attr('src'); 
          $populartitle = $elements->filter('div.views-field-name span.field-content a')->text(); 
          $popularlinks = $elements->filter('div.views-field-name span.field-content a')->attr('href');
        $popularlist []= [
          'popularlogo' => $popularlogo,
          'populartitle' => $populartitle,
          // 'popularlinks' => $popularlinks,
          'popularlinks' => substr($popularlinks,8),
        ];
      });
        
          // print_r( $brandname.'||||'.$brandimg.'||||'.$saleslist.'||||'.$popularlist.'||||');
          // print_r($popularlist);
        return view('allsalesofbrand', compact(['brandname','brandimg','brandtitle','brandlink','saleslist','popularlist',]));
    }

    ////////////// catogriespost by catgname start
    ////////////// catogriespost by catgname start
    ////////////// catogriespost by catgname start
    ////////////// catogriespost by catgname start
    ////////////// catogriespost by catgname start
    ////////////// catogriespost by catgname start
    public function categoriespostf ($id){
        $client = new Client();
        // https://whatsonsale.com.pk/categories/fashion-accessories
        $url = "https://whatsonsale.com.pk/categories/$id";
        $vpage = $client->Request('GET', $url);
        

        $catgname = $vpage->filter('section h1.page-header')->text();

          $catgpostslist = [];
          $vpage->filter('div.view-id-taxonomy_term div.view-content div.views-row')->each(function ($elements) use (&$catgpostslist) {
          $catgpostimg = $elements->filter('div.thread div.thread-thumbnail img.img-responsive')->attr('src');
          $catgpostlink = $elements->filter('div.thread div.thread-thumbnail a')->attr('href');
          $catgposttitle = $elements->filter('div.thread strong.thread-title a')->text();
          $catgpostdesc = $elements->filter('div.thread div.thread-description p')->text();
          $catgpostdate = $elements->filter('div.thread ul.subtext span.post-date')->text();

          $catgpostslist [] = [
            'catgpostimg' => $catgpostimg,
            'catgpostlink' => $catgpostlink,
            'catgposttitle' => $catgposttitle,
            'catgpostdesc' => $catgpostdesc,
            'catgpostdate' => $catgpostdate,
          ];
        });

    
          //---------------------------------------------------------------------//
          return view('categories', compact(['catgname','catgpostslist',]));
          //---------------------------------------------------------------------//
    
          // echo "<pre>";
          // print_r($catgname);
    
        }

        ////////// view all categories page
    public function vallcatgf (){
        $client = new Client();
        $url = "https://whatsonsale.com.pk/stores";
        $vpage = $client->Request('GET', $url);
        

        $catgandstorespath = $client->Request('GET', 'https://whatsonsale.com.pk/categories');

        $catglist = [];
        $catgandstorespath->filter('div.region-content section#block-system-main div.view-content div')->each(function ($elements) use (&$catglist) {
        $catglink = $elements->filter('a')->attr('href');
        $catgname = $elements->filter('a')->text(); 
        $catglist []= [
          'catglink' => $catglink,
          'catgname' => $catgname,
        ];
      });

          $catglistwithicons = [];
          $vpage->filter('section#block-views-categories-block-1 div.view-categories div.view-content div.views-row')->each(function ($elements) use (&$catglistwithicons) {
          $catglink = $elements->filter('span a')->attr('href');
          $catgicon = $elements->filter('span a img.img-responsive')->attr('src');
          $catgname = $elements->filter('span a span.name')->text();
          
          $catglistwithicons [] = [
            'catglink' => $catglink,
            'catgicon' => $catgicon,
            'catgname' => $catgname,
          ];
        });

    
          //---------------------------------------------------------------------//
          return view('allcatg', compact(['catglistwithicons','catglist']));
          //---------------------------------------------------------------------//
    
          // echo "<pre>";
          // print_r($catglistwithicons);
    
        }
        




        /////// view all brands funtion start 
        /////// view all brands funtion start 
        /////// view all brands funtion start 
        /////// view all brands funtion start 
        /////// view all brands funtion start 
        /////// view all brands funtion start 
    public function vallbrandsf (){
        $client = new Client();
        // $url = "https://whatsonsale.com.pk/stores";
        // $vpage = $client->Request('GET', $url);
        $catgandstorespath = $client->Request('GET', 'https://whatsonsale.com.pk/categories');





        $popularbrandslist = [];
        $catgandstorespath->filter('section#block-views-stores-block-1 div.view-id-stores div.view-content div.views-row ')->each(function ($elements) use (&$popularbrandslist) {
            $brandsimage = $elements->filter('a span.image img.img-responsive')->attr('src');
            $brandsname = $elements->filter('a span.name')->text();
            $brandslink = $elements->filter('a')->attr('href');
    
            $popularbrandslist[] = [
                'brandsname' => $brandsname,
                'brandslink' => $brandslink,
                'brandsimage' => $brandsimage,
            ];
        });
    
          //---------------------------------------------------------------------//
          return view('allbrands', compact(['popularbrandslist',]));
          //---------------------------------------------------------------------//
    
          // echo "<pre>";
          // print_r($catglistwithicons);
    
        }
}








// <?php echo $key['catglink']; 





      /* ------------------------ 1st --------------*/
    // $client = new Client();
    // $url  = 'https://www.worldometers.info/coronavirus/';
    // $vpage = $client->Request('GET', $url);
    // echo "<pre>";
    // print_r($vpage);
    // echo $vpage->filter(selector:'.maincounter-number')->text();
    //   $vpage->filter(selector:'#maincounter-wrap')->each(function ($item){
    //     $this->results[$item->filter('h1')->text()]
    //      = $item->filter('.maincounter-number')->text();
    //   });
    // //   return $this->results;
    //   $data = $this->results;
    //   return view('scrap', compact('data'));
    // return 'welcome for scrap';
    // return view(view: 'scrap');
    /*------------------ 1st end ------------------*/
    // <!-- @foreach($data as $key => $value)
    // <h5 style='color:green'>{{$key}}</h5>
    // <p style='color:silver'>{{$value}}</p>
    // @endforeach -->