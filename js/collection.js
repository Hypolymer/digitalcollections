// Javascript for collection view

// Variables
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Default Search Parameters (pre form submission)
var searchDefs = {};
var mergedParams = {};
searchDefs.rows = 10;
searchDefs.start = 0;
searchDefs.wt = "json";
searchDefs.facet = 'true';
searchDefs.facets = [];
searchDefs.facets.push("dc_date","dc_subject","dc_creator","dc_language","rels_hasContentModel");
searchDefs.fq = [];
searchDefs.fl = "id dc_title";
searchDefs['facet.mincount'] = 2;

// Global API response data
APIdata = new Object();

// Facet Hash
var facetHash = {
	"dc_date":"Date",
	"dc_subject":"Subject",
	"dc_creator":"Creator",
	"dc_language":"Language",
	"rels_hasContentModel":"Type"
}

// Content Type Hash 
var contentTypeHash= {
	"info:fedora/CM:Image" : "Image",
	"info:fedora/CM:Document" : "Document",
	"info:fedora/CM:WSUebook" : "WSUebook",
	"info:fedora/CM:Collection" : "Collection",
	"info:fedora/singleObjectCM:WSUebook" : "WSUebook"	
}


// PAGE UPDATE
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

function updatePage(){

	// get current URL
	var cURL = document.URL;

	// update number of results
	$("#q_string").html(mergedParams.q);	
	$("#num_results").html(APIdata.solrSearch.response.numFound);

	// update rows selecctor
	$("#rows").val(mergedParams.rows).prop('selected',true);

	// update query box
	$("#q").val(mergedParams.q);

	// show "refined by" facets
	for (var i = 0; i < mergedParams.fq.length; i++){		
		var facet_string = mergedParams.fq[i];		
		console.log(facet_string);
		var facet_type = facet_string.split(":")[0];
		//content_model special case
		if (facet_string.contains('rels_hasContentModel')){			
			var facet_value = facet_string.replace("rels_hasContentModel:","");
			facet_value = facet_value.replace('info:fedora/CM:','');
		}
		else{						
			var facet_value = facet_string.split(":")[1];				
		}
		var nURL = cURL.replace(("fq[]="+encodeURI(facet_string)),'');
		$("#facet_refine_list").append("<li>"+facetHash[facet_type]+": "+facet_value+" <a href='"+nURL+"'>x</a></li>");
	}

	// pagination
	var tpages = parseInt((APIdata.solrSearch.response.numFound / mergedParams.rows) + 1);
	var spage = parseInt(mergedParams.start / mergedParams.rows) + 1;
	if (spage == 0) {
		spage = 1;
	}

	
	$('.pagination').bootpag({
	   total: tpages,
	   page: spage,
	   maxVisible: 10,
	   leaps:true
	}).on('page', function(event, num){			    
	    var nURL = updateURLParameter(window.location.href, "start", ((num * mergedParams.rows) - mergedParams.rows) );
	    // refresh page	
		window.location = nURL;
	});

}


// QUERYING
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function searchGo(){

	// Set Search Parameters	
	// Pre-merge? Push default facets to params, such that they don't overwrite? May not be necessary, facets should be hardcoded...	
	// Merge default and URL search parameters
	///Gotta encode object/string property value
	searchParams2 = JSON.stringify(searchParams);
	console.log(searchParams2);
	searchParams2 = escape(searchParams2);
	console.log(searchParams2);
	// searchParams ($.parseJSON(searchParams));
	// console.log(searchParams);
	mergedParams = jQuery.extend(true,{},searchDefs,searchParams);
	debugSearchParams();
	
	
	//pass solr parameters os stringify-ed JSON, accepted by Python API as dicitonary
	solrParamsString = JSON.stringify(mergedParams);
	console.log(solrParamsString);

	// Calls API functions
	var APIcallURL = "http://silo.lib.wayne.edu/api/index.php?functions='solrSearch'&GETparams='"+solrParamsString+"'";			

	$.ajax({          
	  url: APIcallURL,      
	  dataType: 'jsonp',	  
	  jsonpCallback: "jsonpcallback",          
	  success: callSuccess,
	  // error: callError
	});

	function callSuccess(response){

	    APIdata = response;
	    console.log("APIdata");
	    console.log(APIdata);
	    $(document).ready(function(){
	    	updatePage();
	    	// populateFacets();
	    	populateResults();	    		
	    });
	    
	}

	function callError(response){
		console.log("API Call unsuccessful.  Back to the drawing board.");	  
	}
}

function updateCollection(){

	// get current URL
	var cURL = document.URL;

	// check rows to update
	searchParams.q = $("#q").val();
	var nURL = updateURLParameter(window.location.href, 'q', searchParams.q);

	// refresh page	
	window.location = nURL;
}


function updateSearch(){

	// get current URL
	var cURL = document.URL;

	// check rows to update
	searchParams.rows = $("#rows").val();
	var nURL = updateURLParameter(window.location.href, 'rows', searchParams.rows);

	// refresh page	
	window.location = nURL;
}



//DISPLAY RESULTS
//////////////////////////////////////////////////////////////////
function populateResults(){
	
	//push results to results_container
	for (var i = 0; i < APIdata.solrSearch.response.docs.length; i++) {		

  		$.ajax({          
		  url: 'templates/searchResultObj.htm',      
		  dataType: 'html',            
		  async:false,
		  success: function(response){		  	
		  	var template = response;
		  	var html = Mustache.to_html(template, APIdata.solrSearch.response.docs[i]);		  	
		  	$("#results_container").append(html);
		  }		  
		});
	}	
}

// UTILITIES
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * http://stackoverflow.com/a/10997390/11236
 */
function updateURLParameter(url, param, paramVal){
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (i=0; i<tempArray.length; i++){
            if(tempArray[i].split('=')[0] != param){
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

function debugSearchParams(){
	console.log("searchParams:");
	console.log(searchParams);
	console.log("searchDefs:");
	console.log(searchDefs);
	console.log("mergedParams:");
	console.log(mergedParams);

}

function removeParameter(url, parameter)
{
  var fragment = url.split('#');
  var urlparts= fragment[0].split('?');

  if (urlparts.length>=2)
  {
    var urlBase=urlparts.shift(); //get first part, and remove from array
    var queryString=urlparts.join("?"); //join it back up

    var prefix = encodeURIComponent(parameter)+'=';
    var pars = queryString.split(/[&;]/g);
    for (var i= pars.length; i-->0;) {               //reverse iteration as may be destructive
      if (pars[i].lastIndexOf(prefix, 0)!==-1) {   //idiom for string.startsWith
        pars.splice(i, 1);
      }
    }
    url = urlBase+'?'+pars.join('&');
    if (fragment[1]) {
      url += "#" + fragment[1];
    }
  }
  return url;
}

function facetCollapseToggle(type, facet){
	$("#"+facet+"_less").toggle();
	$("#"+facet+"_more").toggle();	
	if (type == "more"){
		$("#"+facet+"_list.facet_list li.hidden_facet").fadeIn();			
	}
	if (type == "less"){
		$("#"+facet+"_list.facet_list li.hidden_facet").hide();		
	}	
}

//string contains
String.prototype.contains = function(it) { return this.indexOf(it) != -1; };