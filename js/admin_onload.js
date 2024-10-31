jQuery(document).ready(function($){
	$(".toggler").live("click", function(e){
		e.preventDefault();
		$("#" + $(this).attr("forId")).slideToggle("fast");
	});

	$(".wrap>ul").tabs();

	$("#formReferrerDetector_Add").submit(function(e){
		e.preventDefault();
		if (!validateForm(this)) return false;
		var f = this;
		$("#divLoadingAdd").fadeIn("fast", function(){
			$.ajax({
				url		: $(f).attr("action"),
				type	: $(f).attr("method"),
				data	: serializeForm(f),
				success	: function(msg){
					$("#referrerDetectorEntries tbody tr:first").before(msg);
					$(".wrap>ul").tabs("select", 0);
					toggleForm(f, true);
					$("#divLoadingAdd").fadeOut();
				},
				error	: function(msg){
					alert("Nothing changed...");
					toggleForm(f, true);
					$("#divLoadingAdd").fadeOut();
				}
			});
		});
	});

	$("#formReferrerDetector_Edit").submit(function(e){
		e.preventDefault();
		if (!validateForm(this)) return false;
		var f = this;
		$("#divLoadingEdit").fadeIn("fast", function(){
			$.ajax({
				url		: $(f).attr("action"),
				type	: $(f).attr("method"),
				data	: serializeForm(f),
				success	: function(msg){
					var entryId = $(f).find("input:hidden[name='id']").val();
					$(".wrap>ul").tabs("select", 0);
					$("#editInstruction").show();
					$("#entry_" + entryId).replaceWith(msg);
					$("#divEditContainer").slideUp("fast", function(){
						$(f).html("");
					});
					$("#divLoadingEdit").fadeOut();
				},
				error	: function(msg){
					alert("Nothing changed...");
					toggleForm(f, true);
					$("#divLoadingEdit").fadeOut();
				}
			});
		});
	});

	$(".generic_form").submit(function(e){
		e.preventDefault();
		var f = this;
		$("#divGenericResult").hide();
		$("#divGenericLoading").fadeIn("fast", function(){
			$.ajax({
				url		: $(f).attr("action"),
				type	: $(f).attr("method"),
				data	: serializeForm(f),
				success	: function(msg){
					$("#divGenericResult").html(msg).show();
					toggleForm(f, true);
					$("#divGenericLoading").fadeOut();
				},
				error	: function(msg){
					alert("Nothing changed...");
					toggleForm(f, true);
					$("#divGenericLoading").fadeOut();
				}
			});
		});
	});

	$("a.ajax").live("click", function(e){
		e.preventDefault();
		if ($(this).is(".need_confirm") && !confirm($(this).attr("confirmtext")))
		{
			return false;
		}

		var entryId = $(this).attr("entry");
		var action = $(this).attr("action");
		var a = this;

		if (action == "restore")
		{
			$("#referrerDetectorEntries").fadeOut();
		}

		$("#loading_" + entryId).fadeIn("fast", function(){
			$.ajax({
				url		: "index.php",
				type	: "post",
				data	: "id=" + entryId + "&rdetector_action=" + action + "&nonce=" + getNonce(),
				success	: function(msg){
					switch(action){
						case "activate":
							$("#entry_" + entryId).addClass("active");
							$(a).html(msg).attr("action", "deactivate");
							break;
						case "deactivate":
							$("#entry_" + entryId).removeClass("active");
							$(a).html(msg).attr("action", "activate");
							break;
						case "edit":
							$("#editInstruction").hide();
							$("#formReferrerDetector_Edit").html(msg);
							$(".wrap>ul").tabs("select", 2);
							$("#divEditContainer").slideDown("fast");
							break;
						case "delete":
							$("#entry_" + entryId).fadeOut("fast", function(){
								$(this).remove();
							});

							// in case this is being edited?
							if ($("#formReferrerDetector_Edit").find("input:hidden[name='id']").val() == entryId)
							{
								$("#divEditContainer").slideUp("fast", function(){
									$("#formReferrerDetector_Edit").html("");
								});
							}
							break;
						case "restore":
							$("#referrerDetectorEntries").replaceWith(msg);
							break;
						default:
							break;
					}

					$("#loading_" + entryId).fadeOut();
				},
				error	: function(msg){
					alert("Nothing changed...");
					$("#loading_" + entryId).fadeOut();
				}
			});
		});
	});

	$("#buttonCancel").live("click", function(e){
		$(".wrap>ul").tabs("select", 0);
	});

	$(".toggled").css("display", "none");

	$(".toggler").click(function(e){
		e.preventDefault();
		$(this).next(".toggled:first").slideToggle();
	});

	//showStats($);
	$("#formStats").submit(function(e){
		e.preventDefault();
		var f = this;
		$("#statsHolder").fadeOut("fast", function(){
			$("#statsLoading").fadeIn("fast", function(){
				$.ajax({
					url		: 'index.php',
					type	: 'post',
					data	: serializeForm(f),
					success	: function(msg){
						$("#statsHolder").html(msg).show();
						$("#statsLoading").fadeOut();
					},
					error	: function(msg){
						alert("Problem...");
						$("#statsLoading").fadeOut();
					}
				});
			});
		});
	});

	$("a.stats_detail").live("click", function(e){
		e.preventDefault();
		var $a = $(this);
		$("#statsDetailsContent").fadeOut("fast", function(){
			$("#statsDetailsLoading").fadeIn("fast", function(){
				$.ajax({
					url		: 'index.php',
					type	: 'post',
					data	: "s=" + $a.attr("s") + "&e=" + $a.attr("e") + "&r=" + $a.attr("r") + "&rdetector_action=stat-details&nonce=" + getNonce(),
					success	: function(msg){
						$("#statsDetailsContent").html(msg).show();
						$("#statsDetailsLoading").fadeOut();
					},
					error	: function(msg){
						alert("Problem...");
						$("#statsDetailsLoading").fadeOut();
					}
				});
			});
		});
	});

	$("#timeRange").change(function(){
		$(this).val() == "specified" ? $("#specifiedSpan").show() : $("#specifiedSpan").hide();
	});


	// bulk actions
	$(".check-column input:checkbox").live("change", function(){
		if ($(this).is(":not(:checked)"))
			$(".entry-check:checked").click();
		else
			$(".entry-check:not(:checked)").click();
	});

	$("input#bulkSubmit").click(function(){
		var action = $("#bulkSelect").val();
		if (action == "nope" || $(".entry-check:checked").length == 0) return false;
		if (action == "bulk-merge" && $(".entry-check:checked").length == 1) return false;
		if (action == "bulk-delete" && !confirm("This is not undoable. Are you sure?")) return false;

		var ids = new Array();
		$(".entry-check:checked").each(function(){
			ids.push($(this).val());
		});

		var loadingSelector = "#loading_" + ids.join(",#loading_");
		var entriesSelector = "#entry_" + ids.join(",#entry_");

		$("input#bulkSubmit").attr("disabled", true);
		$(loadingSelector).fadeIn("fast");

		$.ajax({
			url		: "index.php",
			type	: "post",
			data	: "ids=" + ids.toString() + "&rdetector_action=" + action + "&nonce=" + getNonce(),
			success	: function(msg){
				$("input#bulkSubmit").attr("disabled", false);
				$(entriesSelector).fadeOut("fast");
				// alert(msg);
				switch (action){
					case "bulk-delete":
						$(entriesSelector).fadeOut("fast", function(){$(this).remove();});
						break;
					default:
						$("#referrerDetectorEntries").replaceWith(msg);
						break;
				}
			},
			error	: function(msg){
				alert("Nothing changed...");
				$("input#bulkSubmit").attr("disabled", false);
				$(entriesSelector).fadeOut("fast");
				$("#statsLoading").fadeOut();
			}
		});
	});

    $("#formBackup").submit(function(e){
        e.preventDefault();
        if ($("#formBackup input:checked").length == 0)
        {
            return false;
        }

        $("#backupResult").fadeOut("fast", function(){
            $("#divLoadingBackup").fadeIn("fast", function(){
                $.ajax({
                    url     : 'index.php',
                    type    : 'post',
                    data    : $("#formBackup").serialize() + '&nonce=' + getNonce(),
                    success    : function(msg){
                        $("#backupResult").html(msg).show();
                        $("#divLoadingBackup").fadeOut();
                    },
                    error    : function(){
                        alert("Problem...");
                        $("#divLoadingBackup").fadeOut();
                    }
                });
            });
        });
    });

    $("form.ajax").submit(function(){
        return false;
    });


    $('#rdRestore').fileUpload({
        'uploader': '../wp-content/plugins/referrer-detector/js/uploadify/uploader.swf',
        'script': 'index.php',
        'fileExt': '*.rd',
        'scriptData':
            {
                'rdetector_action' : 'restore',
                'nonce': getNonce()
            },
        'folder': '../wp-content/plugins/referrer-detector/upload',
        'cancelImg': '../wp-content/plugins/referrer-detector/js/uploadify/cancel.png',
        'onComplete': function(a, b, c, d){
            $("#backupResult").html(d).fadeIn();
        },
        'onSelectOnce': function(e, d){
        	$("#btnRDRestore").show();
        },
        'onCancel': function(e, d){
        	$("#btnRDRestore").hide();
        },
        'onProgress': function(){
        	$("#btnRDRestore").hide();
        }
    });

    var locMsgCounter = 999;

    $('.add_loc_msg_add').click(function(e){
    	e.preventDefault();
    	locMsgCounter++;
    	$('#locMessagesAdd').append($('#locMessageTemplate').html().replace('__id__', locMsgCounter));
    });

    $('.add_loc_msg_edit').live('click', function(e){
    	e.preventDefault();
    	locMsgCounter++;

    	$('#locMessagesEdit').append($('#locMessageTemplate').html().replace('__id__', locMsgCounter));
    });

    $('.remove_loc_msg').live('click', function(e){
    	e.preventDefault();
    	$(this).parent('div').remove();
    });
});

