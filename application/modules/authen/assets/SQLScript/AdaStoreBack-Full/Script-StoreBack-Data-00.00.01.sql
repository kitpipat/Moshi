--################## CREATE TABLE FOR SCRIPT ##################
	IF OBJECT_ID(N'TCNTUpgradeHisTmp') IS NULL BEGIN
		CREATE TABLE [dbo].[TCNTUpgradeHisTmp] (
					[FTUphVersion] varchar(10) NOT NULL ,
					[FDCreateOn] datetime NULL ,
					[FTUphRemark] varchar(MAX) NULL ,
					[FTCreateBy] varchar(50) NULL 
			);
			ALTER TABLE [dbo].[TCNTUpgradeHisTmp] ADD PRIMARY KEY ([FTUphVersion]);
		END
	GO
--#############################################################

--Version ไฟล์ กับ Version บรรทัดที่ 15 ต้องเท่ากันเสมอ !! 

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.01') BEGIN

--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.01', getdate() , 'สคริปตั้งต้น', 'Nattakit K.');
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.02') BEGIN

	IF NOT EXISTS(SELECT * FROM TSysMenuList WHERE FTMnuCode =  'SPS006') BEGIN
		INSERT INTO TSysMenuList (FTGmnCode,FTMnuParent,FTMnuCode,FNMnuSeq,FTMnuCtlName,FNMnuLevel,FTMnuStaPosHpm,FTMnuStaPosFhn,FTMnuStaSmartHpm,FTMnuStaSmartFhn,FTMnuStaMoreHpm,FTMnuStaMoreFhn,FTMnuType,FTMnuStaAPIPos,FTMnuStaAPISmart,FTMnuStaUse,FTMnuPath,FTGmnModCode,FTMnuImgPath)
		VALUES('SPS', 'SPS', 'SPS006', 5, 'chanel/0/0', 1, 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1', 'Y', 'Y', '1', NULL, 'MAS', NULL)
	END

	IF NOT EXISTS(SELECT * FROM TSysMenuList_L WHERE FTMnuCode =  'SPS006' AND FNLngID = 1) BEGIN
		INSERT INTO TSysMenuList_L(FTMnuCode, FNLngID, FTMnuName, FTMnuRmk)
		VALUES('SPS006', 1, 'ช่องทางการขาย',	NULL)
	END

	IF NOT EXISTS(SELECT * FROM TSysMenuList_L WHERE FTMnuCode =  'SPS006' AND FNLngID = 2) BEGIN
		INSERT INTO TSysMenuList_L(FTMnuCode, FNLngID, FTMnuName, FTMnuRmk)
		VALUES('SPS006', 2, 'Chanel',	NULL)
	END

	IF NOT EXISTS(SELECT * FROM TSysMenuAlbAct WHERE FTMnuCode =  'SPS006') BEGIN
		INSERT INTO TSysMenuAlbAct(FTMnuCode, FTAutStaRead, FTAutStaAdd, FTAutStaEdit, FTAutStaDelete, FTAutStaCancel, FTAutStaAppv, FTAutStaPrint, FTAutStaPrintMore)
		VALUES('SPS006', '1', '1', '1', '1', '0', '0', '0', '0')
	END

	--ทุกครั้งที่รันสคริปใหม่
INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.02', getdate() , 'เพิ่มเมนูช่องทางการขาย', 'IcePun');
END
GO

IF NOT EXISTS(SELECT FTUphVersion FROM TCNTUpgradeHisTmp WHERE FTUphVersion=  '00.00.03') BEGIN

	IF NOT EXISTS(SELECT * FROM TCNTAuto WHERE FTSatTblName =  'TCNMChannel' AND FTSatFedCode = 'FTChnCode' ) BEGIN
		INSERT INTO TCNTAuto (FTSatTblName,FTSatFedCode,FTSatStaDocType,FTSatGroup,FTGmnCode,FTSatDocTypeName,FTSatStaAlwChr,FTSatStaAlwBch,FTSatStaAlwPosShp,FTSatStaAlwYear,FTSatStaAlwMonth,FTSatStaAlwDay,FTSatStaAlwSep,FTSatStaDefUsage,FTSatDefChar,FTSatDefBch,FTSatDefPosShp,FTSatDefYear,FTSatDefMonth,FTSatDefDay,FTSatDefSep,FTSatDefNum,FTSatDefFmtAll,FNSatMaxFedSize,FNSatMinRunning,FTSatUsrChar,FTSatUsrBch,FTSatUsrPosShp,FTSatUsrYear,FTSatUsrMonth,FTSatUsrDay,FTSatUsrSep,FTSatUsrNum,FTSatUsrFmtAll,FTSatStaReset,FTSatStaRunBch,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy)
		VALUES('TCNMChannel', 'FTChnCode', '0', '1', 'MSAL', NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', '0', '0', '0', '0', '0', '00001', '#####', 5, 5, NULL, '0', '0', '0', '0', '0', '0', '00001', '#####', '', '0', '2021-01-15 00:00:00.000', NULL, '2021-01-15 00:00:00.000', NULL)
	END

INSERT INTO [TCNTUpgradeHisTmp] ([FTUphVersion], [FDCreateOn], [FTUphRemark], [FTCreateBy]) VALUES ( '00.00.03', getdate() , 'เพิ่มTCNMChannelในTCNTAuto', 'IcePun');
END
GO