(function($){var intr,totalSeconds=0,isTimerRunning=false,startTime,duration=null,options={seconds:0,editable:false,restart:false,duration:null,callback:function(){alert("Time up!");stopTimerInterval()},repeat:false,countdown:false,format:null,updateFrequency:500},$el,display="html",TIMER_STOPPED="stopped",TIMER_RUNNING="running",TIMER_PAUSED="paused";function startTimerInterval(){intr=setInterval(incrementSeconds,options.updateFrequency);isTimerRunning=true}function stopTimerInterval(){clearInterval(intr);isTimerRunning=false}function incrementSeconds(){totalSeconds=getUnixSeconds()-startTime;render();if(duration&&totalSeconds===duration){options.callback();if(options.repeat){duration+=options.duration}if(options.countdown){options.countdown=false}}}function render(){var sec=totalSeconds;if(options.countdown&&duration>0){sec=duration-totalSeconds}$el[display](secondsToTime(sec));$el.data("seconds",sec)}function makeEditable(){$el.on("focus",function(){pauseTimer()});$el.on("blur",function(){var val=$el[display](),valArr;if(val.indexOf("sec")>0){totalSeconds=Number(val.replace(/\ssec/g,""))}else{if(val.indexOf("min")>0){val=val.replace(/\smin/g,"");valArr=val.split(":");totalSeconds=Number(valArr[0]*60)+Number(valArr[1])}else{if(val.match(/\d{1,2}:\d{2}:\d{2}/)){valArr=val.split(":");totalSeconds=Number(valArr[0]*3600)+Number(valArr[1]*60)+Number(valArr[2])}}}resumeTimer()})}function getUnixSeconds(){return Math.round(new Date().getTime()/1000)}function sec2TimeObj(sec){var hours=0,minutes=Math.floor(sec/60),seconds;if(sec>=3600){hours=Math.floor(sec/3600)}if(sec>=3600){minutes=Math.floor(sec%3600/60)}if(minutes<10&&hours>0){minutes="0"+minutes}seconds=sec%60;if(seconds<10&&(minutes>0||hours>0)){seconds="0"+seconds}return{hours:hours,minutes:minutes,seconds:seconds}}function secondsToTime(sec){var time="",timeObj=sec2TimeObj(sec);if(options.format){var formatDef=[{identifier:"%h",value:timeObj.hours,pad:false},{identifier:"%m",value:timeObj.minutes,pad:false},{identifier:"%s",value:timeObj.seconds,pad:false},{identifier:"%H",value:parseInt(timeObj.hours),pad:true},{identifier:"%M",value:parseInt(timeObj.minutes),pad:true},{identifier:"%S",value:parseInt(timeObj.seconds),pad:true}];time=options.format;formatDef.forEach(function(format){time=time.replace(new RegExp(format.identifier.replace(/([.*+?^=!:${}()|\[\]\/\\])/g,"\\$1"),"g"),(format.pad)?((format.value<10)?"0"+format.value:format.value):format.value)})}else{if(timeObj.hours){time=timeObj.hours+":"+timeObj.minutes+":"+timeObj.seconds}else{if(timeObj.minutes){time=timeObj.minutes+":"+timeObj.seconds+" min"}else{time=timeObj.seconds+" sec"}}}return time}function timeToSeconds(time){if(!isNaN(Number(time))){return time}var hMatch=time.match(/\d{1,2}h/),mMatch=time.match(/\d{1,2}m/),sMatch=time.match(/\d{1,2}s/),seconds=0;time=time.toLowerCase();if(hMatch){seconds+=Number(hMatch[0].replace("h",""))*3600}if(mMatch){seconds+=Number(mMatch[0].replace("m",""))*60}if(sMatch){seconds+=Number(sMatch[0].replace("s",""))}return seconds}function startTimer(){if(!isTimerRunning){render();startTimerInterval();$el.data("state",TIMER_RUNNING)}}function pauseTimer(){if(isTimerRunning){stopTimerInterval();$el.data("state",TIMER_PAUSED)}}function resumeTimer(){if(!isTimerRunning){startTime=getUnixSeconds()-totalSeconds;startTimerInterval();$el.data("state",TIMER_RUNNING)}}function resetTimer(){startTime=getUnixSeconds();totalSeconds=0;$el.data("seconds",totalSeconds);$el.data("state",TIMER_STOPPED);duration=options.duration}function removeTimer(){stopTimerInterval();$el.data("plugin_"+pluginName,null);$el.data("seconds",null);$el.data("state",null);$el[display]("")}var Timer=function(element,userOptions){var elementType;options=$.extend(options,userOptions);$el=$(element);totalSeconds=options.seconds;startTime=getUnixSeconds()-totalSeconds;$el.data("seconds",totalSeconds);$el.data("state",TIMER_STOPPED);elementType=$el.prop("tagName").toLowerCase();if(elementType==="input"||elementType==="textarea"){display="val"}if(options.duration){duration=options.duration=timeToSeconds(options.duration);if(totalSeconds>=duration){duration=totalSeconds+duration}}if(options.editable){makeEditable()}};Timer.prototype={start:function(){startTimer()},pause:function(){pauseTimer()},resume:function(){resumeTimer()},reset:function(){resetTimer()},remove:function(){removeTimer()}};var pluginName="timer";$.fn[pluginName]=function(options){options=options||"start";return this.each(function(){if(!($.data(this,"plugin_"+pluginName) instanceof Timer)){$.data(this,"plugin_"+pluginName,new Timer(this,options))}var instance=$.data(this,"plugin_"+pluginName);if(typeof options==="string"){if(typeof instance[options]==="function"){instance[options].call(instance)}}if(typeof options==="object"){instance.start.call(instance)}})}})(jQuery);