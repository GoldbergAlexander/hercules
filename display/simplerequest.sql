SELECT 
RevenueDaily.Date, 
Location.Name AS Location,
Actual_Intake, 
Actual_Pre_Tax_Intake,
Actual_Taxable_Intake,
Actual_Tax_Intake,
Tape_Intake,
Tape_Pre_Tax_Intake,
Tape_Taxable_Intake,
TransCount,
CashCount,
CheckCount,
CardUnit,
PayoutReceipt,
CashTape,
CheckTape,
CardTape,
TaxTape,
VehicleSale,
SalesVoid,
TaxVoid,
Memo.Data AS Memo,
Username,
Confirmation.Datetime AS Submited,
IP AS IP
FROM RevenueDaily
LEFT JOIN DailyRevenueEntry
ON idDailyRevenueEntry = RevDaily_idDailyRevenueEntry
LEFT JOIN Memo
ON idDailyRevenueEntry = Memo_idDailyRevEntry
LEFT JOIN Confirmation
ON idDailyRevenueEntry = Con_idDailyRevenueEntry
LEFT JOIN Users
ON idUsers = Con_idUsers
LEFT JOIN Location
ON idLocation = RevDaily_idLocation;
