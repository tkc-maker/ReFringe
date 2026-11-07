const Javascript_utilities = {

    isScreenfieldDataValid(question, answer, allScreenfieldValidations){   
        //Whether a field is required or not, is constrained to certain kinds of data
        //or not, allow an entry of "N/A" to skip by the field:
        if(answer.trim().toLowerCase() === 'n/a'){
            return {
                status: "true", 
                errMsg: ""
            };
        }
        
        //Set the "isRequired" flag, and remove the "*" from "$question" if present:
        var isRequired = false;
        if(question.substring(0, 1) === '*'){
            isRequired = true;
            question = question.slice(1);          
        }  

        //Extract the common language "rule" from "question".
        //Note: We must make sure that only one "::" exists in
        //      "question", to delimit the rule.
        var rule = question.split('::')[0];
        if(rule === question){
            rule = '';
        }

        if(!isRequired && rule === ''){    
            return {
                status: "true", 
                errMsg: ""
            };
        }

        if(isRequired && answer === ''){
           return {
               status: "false",
               errMsg: "This field is required! (Or enter N/A to skip.)"
           };
        }

        if(!isRequired && answer === ''){
            return {
                status: "true",
                errMsg: ""
            };
        }

        if(rule === ''){
            return {
                status: "true",
                errMsg: ""
            };
        }

        //Search for "rule" in "allScreenfieldValidations"
        //and test "answer" against the found "regex":
        for(const item of allScreenfieldValidations){
            if(item.rule.toLowerCase() === rule.toLowerCase()){
                const regex = this.createRegex(item.regex);
                if(!regex.test(answer)){
                    return {
                        status: "false",
                        errMsg: item.errMsg
                    };
                }
                return {
                    status: "true",
                    errMsg: ""
                };
            }
        };

        //If we made it this far, then the rule cannot be found:
        return {
            status: false,
            errMsg: "Rule cannot be found in database."
        };
    },//End function, "isScreenfieldDataValid".
                
    createRegex(regexString) {
        // Remove escaped forward slashes:
        const unescapedString = regexString.replace(/\\\//g, '/');

        // Match a regex string that starts and ends with "/" with optional flags.
        const match = unescapedString.match(/^\/(.*)\/([gimsuy]*)$/);
        if (match) {
          const pattern = match[1];
          const flags = match[2];
          return new RegExp(pattern, flags);
        } else {
          // If it doesn't have the expected delimiters, assume it's a raw pattern.
          return new RegExp(unescapedString);
        }
    }//End function, "createRegex".

};//End "const Javascript_utilities".