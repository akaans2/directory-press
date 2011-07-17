
//Split search category
jQuery(document).ready(function ($) {

    $('.homeCategories span a').css('font-size', '13px');
    $('.homeCategories').hide();
    $('#sidebar-cats-sub').css({'cursor': 'pointer'}).click(function () {
        $('.homeCategories').toggle();
    }).after(' <p>Click to view more options</p>');

    //Clarify category list
    $('.sub a').each(function(i, d) {
        var $this = $(this);
        if ($this.attr('href').match(/^http:\/\/.+\/.+\/.+/)) {
            $this.parent().css('padding-left', '10px').hide()
                    .before('<li class="toggersub" style="padding-left: 10px;"><a href="javascript:void(0)"> ' + $this.parent().text() + '</a></li>')
        }
        if ($this.attr('href').match(/^http:\/\/.+\/.+\/.+\/.+/)) {
            $this.parent().css('padding-left', '20px').hide().prev().remove();
        }
    })

    $('.toggersub').live('click', function () {
        $(this).nextUntil('.toggersub').toggle();
    })

    //Splitting search into 3 boxes feature
    var anchor2 = ' -';
    var anchor3 = ' --';
    var created2 = false;
    var created3 = false;
    var $search = $('#catsearch');
    var $search2;
    var $search3;
    var $catdetach2;
    var $catdetach3;
    var $currentCat;
    var $currentSubcat;
    var $go = $('#SearchForm .searchBtn');

    //Init
    $('#catsearch option:eq(0)').attr('selected', 'selected');
    $('#s').hide().val(' ');
    $('#SearchForm .searchBtn').removeAttr('onclick');
    $('#catsearch option:first').text('All of Cornwall').css('font-weight', 'bold');
    $('#SearchForm tbody').prepend('<tr><td colspan="6" class="white">Search for your perfect cornish holiday today:</td></tr>');

    //Split to 3 comboboxes
    $('#catsearch option').each(function (i, d) {
        var $data = $(d);

        if ($data.text().substr(0, anchor2.length) != anchor2) {
//            $currentCat = $data;
            $data.css('font-weight', 'bold')
        }

        else if ($data.text().substr(0, anchor2.length) == anchor2 && $data.text().substr(3, 1) == ' ') {
            $currentSubcat = $data;
//            if (!created2) {
//                created2 = true;
//                $('<td><select id="catsearch2"></td>').insertBefore($go.parent());
//                $search2 = $('#catsearch2');
//                $('<option value="">All Towns</option>').appendTo($search2).css('font-weight', 'bold');
//            }
//            $data.appendTo($search2).addClass('subcategory-search').addClass($currentCat.val());
//            $data.text($data.text().substr(4));
        }

        else if ($data.text().substr(0, anchor3.length) == anchor3) {
            if (!created3) {
                created3 = true;
                $('<td><select id="catsearch3"></td>').insertBefore($go.parent());
                $search3 = $('#catsearch3');
                $('<option value="">All Accommodation</option>').appendTo($search3).css('font-weight', 'bold');
            }
            $data.appendTo($search3).addClass('subsubcategory-search').addClass($currentSubcat.val());
            $data.text($data.text().substr(4));
        }
    })

    //Hide some towns
//    $catdetach2 = $('.subcategory-search').detach();
    $catdetach3 = $('.subsubcategory-search').detach();

    //Change category
    $search.change(function () {
        $('#catsearch3 option:eq(0)').attr('selected', 'selected');
//        $search2.append($catdetach2);
//        if ($search.attr('selectedIndex') > 0) $catdetach2 = $('.subcategory-search:not(.' + $search.val() + ')').detach();
//        else $catdetach2 = $('.subcategory-search').detach();
        $search3.append($catdetach3);
        if ($search.attr('selectedIndex') > 0) $catdetach3 = $('.subsubcategory-search:not(.' + $search.val() + ')').detach();
        else $catdetach3 = $('.subsubcategory-search').detach();
    })

    //Change category
//    $search2.change(function () {
//        $search3.append($catdetach3);
//        if ($search2.attr('selectedIndex') > 0) $catdetach3 = $('.subsubcategory-search:not(.' + $search2.val() + ')').detach();
//        else $catdetach3 = $('.subsubcategory-search').detach();
//    })

    //Submit search
    $go.click(function () {
        $search.removeAttr('name');
//        $search2.removeAttr('name');
        $search3.removeAttr('name');

        if ($search3.attr('selectedIndex') > 0) $search3.attr('name', 'cat');
//        else if ($search2.attr('selectedIndex') > 0) $search2.attr('name', 'cat');
        else $search.attr('name', 'cat');
        
//        alert($('#searchBox').serialize());
        $('#searchBox').submit();
    })
})

function clearMe(){

document.getElementById("s").value = "";
}
function toggleLayer( whichLayer )
{
  var elem, vis;
  if( document.getElementById ) 
    elem = document.getElementById( whichLayer );
  else if( document.all ) 
      elem = document.all[whichLayer];
  else if( document.layers ) 
    elem = document.layers[whichLayer];
  vis = elem.style;

  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}

function HideThisLayer ( whichLayer ){

  var elem, vis;
  if( document.getElementById ) 
    elem = document.getElementById( whichLayer );
  else if( document.all ) 
      elem = document.all[whichLayer];
  else if( document.layers ) 
    elem = document.layers[whichLayer];
  vis = elem.style;
  vis.display = "none";
}
 