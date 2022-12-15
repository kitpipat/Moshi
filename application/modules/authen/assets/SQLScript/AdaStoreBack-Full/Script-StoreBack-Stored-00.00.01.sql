-------------- Script Stored 00.00.01 ----------------

/****** Object:  StoredProcedure [dbo].[SP_CNtAUTAutoDocNo]    Script Date: 14/12/2565 13:53:41 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[SP_CNtAUTAutoDocNo]') AND type in (N'P', N'PC'))
BEGIN
EXEC dbo.sp_executesql @statement = N'CREATE PROCEDURE [dbo].[SP_CNtAUTAutoDocNo] AS' 
END
GO
-- =============================================
-- Author:		Kitpipat Kaewkieo
-- Create date: 28/04/2020
-- Description:	สร้างรหัสเอกสารอัตโนมัติ
--Version 1 / 28/04/2020
--Version 2 / 13/05/2020 รองรับ Master
-- =============================================
ALTER PROCEDURE [dbo].[SP_CNtAUTAutoDocNo]
	-- Add the parameters for the stored procedure here
	@ptTblName VARCHAR(30),
	@ptDocType VARCHAR(10),
	@ptBchCode VARCHAR(5),
	@ptShpCode VARCHAR(5),
	@ptPosCode VARCHAR(5),
	@pdDocDate DATETIME,
	@ptResult VARCHAR(30) OUTPUT
AS
BEGIN TRY
     --Def Setting
	 DECLARE @tDefChar VARCHAR(5)
	 DECLARE @tDefBch VARCHAR(1)
	 DECLARE @tDefPosShp VARCHAR(1)
	 DECLARE @tDefYear VARCHAR(1)
	 DECLARE @tDefMonth VARCHAR(1)
	 DECLARE @tDefDay VARCHAR(1)
	 DECLARE @tDefSep VARCHAR(1)
	 DECLARE @nMinRunning VARCHAR(1)
	 DECLARE @tDefNum VARCHAR(50)
	 DECLARE @nDefStaReset VARCHAR(1)
	 DECLARE @tDefFmtAll VARCHAR(50)
	 
	 --User Setting
	 DECLARE @tUsrFmtAll VARCHAR(100)
	 DECLARE @tUsrStaReset VARCHAR(100)
	 DECLARE @tUsrFmtReset VARCHAR(100)
	 DECLARE @nUsrNumSize INT
	 DECLARE @tSatUsrNum VARCHAR(20)
	 DECLARE @tUsrFmtPst VARCHAR(100)
	 DECLARE @tUsrChar VARCHAR(50)
	 DECLARE @tUsrStaAlwSep VARCHAR(1)
	 
	 --ตัวแปรกลาง
	 DECLARE @tFedCode VARCHAR(100) 
	 DECLARE @nStaResBch INT
	 DECLARE @nStaResDay INT
	 DECLARE @nStaResMonth INT
	 DECLARE @nStaResYear INT

	 DECLARE @tSQLMaxDoc NVARCHAR(MAX)
	         SET @tSQLMaxDoc = ''

	 DECLARE @tParamMaxDoc NVARCHAR(MAX)
	         SET @tParamMaxDoc = ''
	 
	 DECLARE @tFilterBch NVARCHAR(200)
	         SET @tFilterBch = ''

	 DECLARE @tMaxBchCode VARCHAR(20)
	 DECLARE @tLastNo VARCHAR(20)
	 DECLARE @tLastDay VARCHAR(10)
	 DECLARE @tLastMonth VARCHAR(10)
	 DECLARE @tLastYear VARCHAR(10)
	 DECLARE @tRunningNo VARCHAR(10)
	 DECLARE @tAutoFrm VARCHAR(100)
	 DECLARE @tNextDocNo VARCHAR(100)
	 DECLARE @tStartNum VARCHAR(50)
	 DECLARE @nRunningSize INT
	 DECLARE @tFTBchCode VARCHAR(50) --13/05/2020 

	 --การหาวัน-เดือน-ปีที่ต้องการ Gen Code
	 DECLARE @tSaleYear VARCHAR(10)
	 DECLARE @tSaleMonth VARCHAR(10)
	 DECLARE @tSaleDate VARCHAR(10)
	 SET @tSaleYear = SUBSTRING(CONVERT(VARCHAR(10),@pdDocDate,121),1,4)
	 SET @tSaleMonth = SUBSTRING(CONVERT(VARCHAR(10),@pdDocDate,121),6,2)
	 SET @tSaleDate = SUBSTRING(CONVERT(VARCHAR(10),@pdDocDate,121),9,2)
	 
     --Get Config
	 SELECT   @tDefChar=ISNULL(AUT.FTSatDefChar,'')
	         ,@tDefBch = ISNULL(AUT.FTSatDefBch,0)
			 ,@tDefPosShp = ISNULL(AUT.FTSatDefPosShp,0)
			 ,@tDefYear = ISNULL(AUT.FTSatDefYear,0)
			 ,@tDefMonth = ISNULL(AUT.FTSatDefMonth,0)
			 ,@tDefDay = ISNULL(AUT.FTSatDefDay,0)
			 ,@tDefSep = ISNULL(AUT.FTSatDefSep,0)
			 ,@nDefStaReset = ISNULL(FTSatStaReset,0)
			 ,@tFedCode = AUT.FTSatFedCode
			 ,@nMinRunning = ISNULL(AUT.FNSatMinRunning,0)
			 ,@tDefNum = ISNULL(FTSatDefNum,1)
			 ,@tUsrFmtAll = ISNULL(TXN.FTAhmFmtAll,'')
			 ,@tUsrStaReset = ISNULL(TXN.FTAhmStaReset,0)
			 ,@tUsrFmtReset = ISNULL(TXN.FTAhmFmtReset,'')
			 ,@nUsrNumSize = ISNULL(TXN.FNAhmNumSize,5)
			 ,@tSatUsrNum = TXN.FTSatUsrNum
			 ,@tUsrFmtPst = TXN.FTAhmFmtPst
			 ,@tUsrChar = TXN.FTAhmFmtChar
			 ,@tUsrStaAlwSep = ISNULL(TXN.FTSatStaAlwSep,0)
			 ,@tDefFmtAll = ISNULL(AUT.FTSatDefFmtAll,0)

	 FROM    TCNTAuto AUT
	 LEFT    JOIN TCNTAutoHisTxn TXN ON AUT.FTSatTblName = TXN.FTAhmTblName 
								 AND AUT.FTSatFedCode = TXN.FTAhmFedCode
								 AND AUT.FTSatStaDocType = TXN.FTSatStaDocType
	 WHERE   AUT.FTSatTblName = @ptTblName
	 AND     AUT.FTSatStaDocType = @ptDocType

	 ------------------------------------------------------------------------------------------------------------------------------------------
	 --หาเลข auto running ล่าสุด
	IF(@tUsrFmtAll <> '')
	   BEGIN
			SET @nUsrNumSize = @nUsrNumSize
	        IF(CHARINDEX('BCH', @tUsrFmtReset) <> 0)
			  BEGIN
			       SET @tFilterBch+= ' AND FTBchCode='''+@ptBchCode+''''
				   SET @tFTBchCode = 'FTBchCode' --13/05/2020
			  END
			ELSE
			  BEGIN
			       SET @tFilterBch+= ''
				   SET @tFTBchCode = '''''' --13/05/2020
			  END
	   END
	ELSE
	   BEGIN
		   SET @nUsrNumSize = LEN(@tDefNum)
	       IF(@nDefStaReset = 4)
		      BEGIN
			     SET @tFilterBch+= ' AND FTBchCode='''+@ptBchCode+''''
				 SET @tFTBchCode = 'FTBchCode' --13/05/2020
			  END
		   ELSE
		     BEGIN
			   SET @tFilterBch+= ''
			   SET @tFTBchCode = '''''' --13/05/2020
			 END
	   END


	--Loop หา Format Supawat 08/09/2020
	DECLARE @nPosition_CHECK	INT
	DECLARE @nLen_CHECK			INT
	DECLARE @nNum				INT
	DECLARE @tFrmType_CHECK		VARCHAR(8000)
	DECLARE @tUsrFmtPst_CHECK	VARCHAR(100)
	DECLARE @tCheckFormat		VARCHAR(800)
	SET		@tCheckFormat		= ''

	IF(@tUsrFmtAll <> '')
	   -- มีการเซตในตาราง TXN จัดรูปแบบเอง
		BEGIN
			SET	@nNum					= 1
			SET @tUsrFmtPst_CHECK		= @tUsrFmtPst + ','
			SET @nPosition_CHECK		= 0
			SET @nLen_CHECK				= 0

			WHILE CHARINDEX(',', @tUsrFmtPst_CHECK, @nPosition_CHECK + 1 ) > 0
				BEGIN
					SET @nLen_CHECK		= CHARINDEX(',', @tUsrFmtPst_CHECK, @nPosition_CHECK + 1 ) - @nPosition_CHECK
					SET @tFrmType_CHECK	= SUBSTRING(@tUsrFmtPst_CHECK, @nPosition_CHECK, @nLen_CHECK)

					IF(@tFrmType_CHECK = 'CHA')
						BEGIN
							SET @tCheckFormat = @tCheckFormat + @tUsrChar
						END
					ELSE IF(@tFrmType_CHECK = 'BCH')
						BEGIN
							SET @tCheckFormat = @tCheckFormat + '[0-9A-Z][0-9A-Z][0-9A-Z][0-9A-Z][0-9A-Z]'
						END

					ELSE IF(@tFrmType_CHECK = 'PSH')
						BEGIN
							SET @tCheckFormat = @tCheckFormat + '[0-9A-Z][0-9A-Z][0-9A-Z][0-9A-Z][0-9A-Z]'
						END
					
					ELSE IF(@tFrmType_CHECK = 'YYYY')
						BEGIN
							SET @tCheckFormat = @tCheckFormat + '[0-9][0-9][0-9][0-9]'
						END

					ELSE IF(@tFrmType_CHECK = 'YY')
						BEGIN
							SET @tCheckFormat = @tCheckFormat + '[0-9][0-9]'
						END

					ELSE IF(@tFrmType_CHECK = 'MM')
						BEGIN
							SET @tCheckFormat = @tCheckFormat + '[0-9][0-9]'
						END

					ELSE IF(@tFrmType_CHECK = 'DD')
						BEGIN
							SET @tCheckFormat = @tCheckFormat + '[0-9][0-9]'
						END
			
					--Loop ใหม่อีกรอบ
					SET @nPosition_CHECK = CHARINDEX(',', @tUsrFmtPst_CHECK, @nPosition_CHECK + @nLen_CHECK) + 1
			END

			--#####
			WHILE (@nNum <= @nUsrNumSize)
				BEGIN
					SET @tCheckFormat	+= '[0-9]'
					SET @nNum			 = @nNum + 1
				END
		END
	ELSE
		BEGIN
			SET		@nNum = 1
				BEGIN
					--ต้องเอา Format ใน Default มาหา

					--รูปแบบของ รหัสขึ้นต้น
					IF(@tDefChar <> '' OR @tDefChar <> null OR @tDefChar <> 0)
						SET @tCheckFormat += @tDefChar
					ELSE
						SET @tCheckFormat += ''
					
					--รูปแบบของ สาขา
					IF(@tDefBch <> 0)
						SET @tCheckFormat += '[0-9A-Z][0-9A-Z][0-9A-Z][0-9A-Z][0-9A-Z]'
					ELSE
						SET @tCheckFormat += ''

					--รูปแบบของ จุดขาย
					IF(@tDefPosShp <> 0)
						IF(@ptPosCode <> '')
							SET @tCheckFormat += '[0-9A-Z][0-9A-Z][0-9A-Z][0-9A-Z][0-9A-Z]'
						ELSE
							SET @tCheckFormat += ''
					ELSE
						SET @tCheckFormat += ''

					--รูปแบบของ ปี
					IF(@tDefYear <> 0)
						SET @tCheckFormat += '[0-9][0-9]'
					ELSE
						SET @tCheckFormat += ''

					--รูปแบบของ เดือน
					IF(@tDefMonth <> 0)
						SET @tCheckFormat += '[0-9][0-9]'
					ELSE
						SET @tCheckFormat += ''

					--รูปแบบของ วัน
					IF(@tDefDay <> 0)
						SET @tCheckFormat += '[0-9][0-9]'
					ELSE
						SET @tCheckFormat += ''

					--รูปแบบของ คั่นกลาง
					IF(@tDefSep <> 0)
						SET @tCheckFormat += '[-]'
					ELSE
						SET @tCheckFormat += ''

					--#####
					WHILE (@nNum <= @nUsrNumSize)
					BEGIN
						SET @tCheckFormat += '[0-9]'
						SET @nNum = @nNum + 1
					END
				END
	END

  --PRINT(@tCheckFormat);
	--return;

	SET @tSQLMaxDoc+= ' SELECT TOP 1 @tMaxBchCodeOUT = '+@tFTBchCode --13/05/2020
	SET @tSQLMaxDoc+= ' ,@tLastNoOUT = RIGHT('+@tFedCode+','+CAST(@nUsrNumSize AS VARCHAR(10))+')'
	SET @tSQLMaxDoc+= ' ,@tLastDayOUT = SUBSTRING(CONVERT(VARCHAR(10),FDCreateOn,121),9,2)'
	SET @tSQLMaxDoc+= ' ,@tLastMonthOUT = SUBSTRING(CONVERT(VARCHAR(10),FDCreateOn,121),6,2)'
	SET @tSQLMaxDoc+= ' ,@tLastYearOUT = SUBSTRING(CONVERT(VARCHAR(10),FDCreateOn,121),1,4)'
	SET @tSQLMaxDoc+= ' FROM '+@ptTblName
	SET @tSQLMaxDoc+= ' WHERE '
	SET @tSQLMaxDoc += ' '+@tFedCode+' = '
	SET @tSQLMaxDoc += ' ( SELECT TOP 1 '+@tFedCode+' FROM '+@ptTblName+' WHERE '+@tFedCode+' LIKE '''+@tCheckFormat+'''  '
	SET @tSQLMaxDoc += ' AND 1=1 ' + @tFilterBch

	IF(@tFilterBch <> '')
		BEGIN
			SET @tSQLMaxDoc += @tFilterBch
		END
	
  SET @tSQLMaxDoc += ' ORDER BY SUBSTRING (CONVERT (VARCHAR(10), FDCreateOn, 121),1,4) DESC , RIGHT('+@tFedCode+','+CAST(@nUsrNumSize AS VARCHAR(10))+') DESC )'
	SET @tSQLMaxDoc+= @tFilterBch

	--PRINT(@tSQLMaxDoc);
	--return;

	SET @tParamMaxDoc+= N' @tMaxBchCodeOUT VARCHAR(20) OUTPUT '
	SET @tParamMaxDoc+= N',@tLastNoOUT VARCHAR(20) OUTPUT '
	SET @tParamMaxDoc+= N',@tLastDayOUT VARCHAR(10) OUTPUT '
	SET @tParamMaxDoc+= N',@tLastMonthOUT VARCHAR(10) OUTPUT '
	SET @tParamMaxDoc+= N',@tLastYearOUT VARCHAR(10) OUTPUT '

	EXECUTE sp_executesql @tSQLMaxDoc,
					        @tParamMaxDoc,
							@tMaxBchCodeOUT = @tMaxBchCode OUTPUT,
							@tLastNoOUT = @tLastNo OUTPUT,
							@tLastDayOUT = @tLastDay OUTPUT,
							@tLastMonthOUT = @tLastMonth OUTPUT,
							@tLastYearOUT = @tLastYear OUTPUT

	 ------------------------------------------------------------------------------------------------------------------------------------------

	 --ตรวจสอบว่ามีการกำหนดการตั้งค่าโดยผู้ใช้หรือไม่
	 IF(@tUsrFmtAll <> '')
	    BEGIN
		     --ตรวจสอบการ reset number
			 IF(@tUsrStaReset <> 0)
			    BEGIN

				    --มีการตั้งค่าให้ reset number
					SET @nStaResBch  = CHARINDEX('BCH', @tUsrFmtReset)
					SET @nStaResYear  = CHARINDEX('YYYY', @tUsrFmtReset)
					SET @nStaResMonth  = CHARINDEX('MM', @tUsrFmtReset)
					SET @nStaResDay  = CHARINDEX('DD', @tUsrFmtReset)

				END
		     ELSE
			    BEGIN
				   --ไม่มีการ reset number ใช้แบบ run ต่อเนื่อง
				    SET @nStaResBch  = 0
					SET @nStaResYear  = 0
					SET @nStaResMonth  = 0
					SET @nStaResDay  = 0

				END

			--จัด Format ตามที่ผู้ใช้ตั้งค่า
			DECLARE @nPosition INT
			DECLARE @nLen INT
			DECLARE @tFrmType varchar(8000)
			SET @tUsrFmtPst = @tUsrFmtPst + ','
			SET @nPosition = 0
			SET @nLen = 0
			SET @tAutoFrm = ''

			WHILE CHARINDEX(',', @tUsrFmtPst, @nPosition+1) > 0
				BEGIN
					SET @nLen = CHARINDEX(',', @tUsrFmtPst, @nPosition+1) - @nPosition
					SET @tFrmType = SUBSTRING(@tUsrFmtPst, @nPosition, @nLen)

					IF(@tFrmType = 'CHA')
						BEGIN
							SET @tAutoFrm = @tAutoFrm + @tUsrChar
						END
					ELSE IF(@tFrmType = 'BCH')
						BEGIN
							SET @tAutoFrm = @tAutoFrm + @ptBchCode
						END

					ELSE IF(@tFrmType = 'PSH')
						BEGIN
							SET @tAutoFrm = @tAutoFrm + @ptShpCode+@ptPosCode
						END
					
					ELSE IF(@tFrmType = 'YYYY' OR @tFrmType = 'YY')
						BEGIN
							SET @tAutoFrm = @tAutoFrm + CONVERT(VARCHAR(4),@pdDocDate,121)
						END

					ELSE IF(@tFrmType = 'MM')
						BEGIN
							SET @tAutoFrm = @tAutoFrm + SUBSTRING(CONVERT(VARCHAR(10),@pdDocDate,121),6,2)
						END

					ELSE IF(@tFrmType = 'DD')
						BEGIN
							SET @tAutoFrm = @tAutoFrm + SUBSTRING(CONVERT(VARCHAR(10),@pdDocDate,121),9,2)
						END
					
					SET @nPosition = CHARINDEX(',', @tUsrFmtPst, @nPosition+@nLen) + 1

			END

			IF(@tUsrStaAlwSep <> 0)
			   BEGIN
			       SET @tAutoFrm = @tAutoFrm + '-'
			   END
			--จบการจัด Format

			--Set Start Number
			SET @tStartNum = @tSatUsrNum
			--SET Running Size Number
			SET @nRunningSize = @nUsrNumSize
		END
	 ELSE
	    BEGIN
		   --ใช้ค่า Def จากระบบ
		   --ตรวจสอบการ reset รหัส
		   IF(@nDefStaReset = 1) BEGIN SET @nStaResYear = @nDefStaReset  END ELSE BEGIN SET @nStaResYear = '0'  END
		   IF(@nDefStaReset = 2) BEGIN SET @nStaResMonth = @nDefStaReset END ELSE BEGIN SET @nStaResMonth = '0' END
		   IF(@nDefStaReset = 3) BEGIN SET @nStaResDay = @nDefStaReset   END ELSE BEGIN SET @nStaResDay = '0'   END
		   IF(@nDefStaReset = 4) BEGIN SET @nStaResBch = @nDefStaReset   END ELSE BEGIN SET @nStaResBch = '0'   END

		   IF(@tLastNo <> '') BEGIN SET @tLastNo = @tLastNo END ELSE BEGIN SET @tLastNo = 0 END

		   SET @nRunningSize = LEN(@tDefNum)
		   --SET @tStartNum = CONCAT(REPLICATE('0',@nRunningSize-LEN(1)),1) 
		   SET @tStartNum = @tDefNum
		   SET @tAutoFrm = ''
		   IF(@tDefChar <> '')  BEGIN SET @tAutoFrm+= @tDefChar  END
		   IF(@tDefBch <> 0)    BEGIN SET @tAutoFrm+= @ptBchCode END
		   IF(@tDefPosShp <> 0) BEGIN SET @tAutoFrm+= @ptPosCode END
		   IF(@tDefYear <> 0)  
		      BEGIN 
			        IF(@tDefYear = 'YYYY')
					   BEGIN
			        SET @tSaleYear = @tSaleYear
							SET @tLastYear = @tLastYear
					   END
					ELSE
					   BEGIN
					     SET @tSaleYear = SUBSTRING(@tSaleYear,3,2)
						   SET @tLastYear = SUBSTRING(@tLastYear,3,2)
					   END     
			        SET @tAutoFrm+=  @tSaleYear 
			  END
			IF(@tDefMonth <> 0)  BEGIN SET @tAutoFrm+= @tSaleMonth END
			IF(@tDefDay <> 0)    BEGIN SET @tAutoFrm+= @tSaleDate  END
			IF(@tDefSep <> 0)    BEGIN SET @tAutoFrm+= '-'  END

		END
     
	 -----------------------------------------------------------------------------------------------------------------------------
	--ตรวจสอบการ reset เลขที่เอกสารตามสาขา 
	IF(@nStaResBch <> 0 )
		BEGIN
		    
			IF(@tMaxBchCode <> '')
				BEGIN 
					SET @tRunningNo = @tLastNo + 1
					SET @tRunningNo = CONCAT(REPLICATE('0',@nRunningSize-LEN(@tRunningNo)),@tRunningNo)
					SET @tNextDocNo = @tAutoFrm+@tRunningNo 
				 
				END
			ELSE
				BEGIN
					SET @tRunningNo = @tStartNum
					SET @tNextDocNo = @tAutoFrm+@tRunningNo
				END
		END
	------------------------------------------------------------------------------------------------------------------------------
	--ตรวจสอบการ reset เลขที่เอกสารตามปี
	ELSE IF(@nStaResYear <> 0)
		BEGIN
		    --PRINT('Reset ตามปี')
			--PRINT('Last Year:'+@tLastYear+'tSaleYear:'+@tSaleYear)
				IF(@tLastYear <> '')
			    BEGIN
			        SET @tLastYear = @tLastYear
					END
				ELSE
			    BEGIN
				    SET @tLastYear = '1900'
					END
			
		    IF(@tLastYear <> @tSaleYear)
			    BEGIN
				    SET @tRunningNo = @tStartNum
				    SET @tNextDocNo = @tAutoFrm+@tRunningNo
					END
				ELSE
			    BEGIN
				    SET @tRunningNo = @tLastNo + 1
						SET @tRunningNo = CONCAT(REPLICATE('0',@nRunningSize-LEN(@tRunningNo)),@tRunningNo)
						SET @tNextDocNo = @tAutoFrm+@tRunningNo
				END
		END

	------------------------------------------------------------------------------------------------------------------------------
	--ตรวจสอบการ reset เลขที่เอกสารตามเดือน
	ELSE IF(@nStaResMonth <> 0)
	     BEGIN
		      IF(@tLastMonth <> '')
			     BEGIN
				       SET @tLastMonth = @tLastMonth
				 END
			  ELSE
			     BEGIN
				      SET @tLastMonth = '00'
				 END
			 IF(@tLastMonth <> @tSaleMonth)
			   BEGIN
			        SET @tRunningNo = @tStartNum
				    SET @tNextDocNo = @tAutoFrm+@tRunningNo
			   END
			 ELSE
			   BEGIN
			       SET @tRunningNo = @tLastNo + 1
				   SET @tRunningNo = CONCAT(REPLICATE('0',@nRunningSize-LEN(@tRunningNo)),@tRunningNo)
				   SET @tNextDocNo = @tAutoFrm+@tRunningNo
			   END
		 END
	------------------------------------------------------------------------------------------------------------------------------
	--ตรวจสอบการ reset เลขที่เอกสารตามวัน
	ELSE IF(@nStaResDay <> 0)
		BEGIN
			IF(@tLastDay <> '')
				BEGIN
					SET @tLastDay = @tLastDay
				END
			ELSE
				BEGIN
					SET @tLastDay = '00'
				END
			IF(@tLastDay <> @tSaleDate)
				BEGIN
					SET @tRunningNo = @tStartNum
					SET @tNextDocNo = @tAutoFrm+@tRunningNo
				END
			ELSE
				BEGIN
					SET @tRunningNo = @tLastNo + 1
					SET @tRunningNo = CONCAT(REPLICATE('0',@nRunningSize-LEN(@tRunningNo)),@tRunningNo)
					SET @tNextDocNo = @tAutoFrm+@tRunningNo
				END
		END
	------------------------------------------------------------------------------------------------------------------------------
	--ใช้การ Run แบบต่อเนื่อง
	ELSE
	   BEGIN
		   IF(@tLastNo <> '')
			  BEGIN
			     SET @tRunningNo = @tLastNo + 1
			  END
			ELSE
			  BEGIN
			    SET @tRunningNo = @tStartNum
			  END

		   SET @tRunningNo = CONCAT(REPLICATE('0',@nRunningSize-LEN(@tRunningNo)),@tRunningNo)

		   SET @tNextDocNo = @tAutoFrm+@tRunningNo
	   END
	------------------------------------------------------------------------------------------------------------------------------

	--เลขที่เอกสารที่ได้
	--PRINT( @tRunningNo )
--return
    SELECT @tNextDocNo AS FTXxhDocNo

END TRY
BEGIN CATCH
     return -1
END CATCH
GO
