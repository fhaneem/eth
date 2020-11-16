pragma solidity ^0.5.15;

contract Marriage {
    
    struct MarriageInfo {
        uint Ic;
        string IndividualName;
        uint spouseIc;
        string spouseName;
        string status;
        string statusDate;
    }
    
    mapping(uint => MarriageInfo) public marriageInfos;
    
    //mapping (address => Instructor) instructors;
    //address[] public instructorAccts;
    
    function setMarriageInfo( uint _Ic, string memory _IndividualName, uint _spouseIc, string memory _SpouseName, string memory _status, string memory _statusDate) public {
     
        Marriage.MarriageInfo storage marriage = marriageInfos[_Ic];
        marriage.Ic = _Ic;
        marriage.IndividualName = _IndividualName;
        marriage.spouseIc = _spouseIc;
        marriage.spouseName = _SpouseName;
        marriage.status = _status;
        marriage.statusDate = _statusDate;
        
    }
    
     function getMarriageInfos(uint _Ic) view public returns (string memory, uint , string memory , string memory , string memory , string memory ) {
        return (marriageInfos[_Ic].IndividualName, marriageInfos[_Ic].spouseIc, marriageInfos[_Ic].spouseName, marriageInfos[_Ic].spouseName, marriageInfos[_Ic].status, marriageInfos[_Ic].statusDate);
    }
	
	/*
	770909109899, 'Muhammad Lutfi bin Ahmad', 720909095454, 'Rashidah binti Jamal', 'N', '2018-03-05'
	*/
    
  
    
}