------------------------ Script Structure 00.00.01 -----------------------

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TCNMZone' AND COLUMN_NAME = 'FTAgnCode') BEGIN
	ALTER TABLE TCNMZone ADD FTAgnCode VARCHAR(20) NOT NULL DEFAULT('')
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TCNMZone_L' AND COLUMN_NAME = 'FTAgnCode') BEGIN
	ALTER TABLE TCNMZone_L ADD FTAgnCode VARCHAR(20)
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TCNMZoneObj' AND COLUMN_NAME = 'FTAgnCode') BEGIN
	ALTER TABLE TCNMZoneObj ADD FTAgnCode VARCHAR(20) 
END
GO


/****** Object:  Table [dbo].[TCNMCountry]    Script Date: 14/12/2565 11:52:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TCNMCountry]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TCNMCountry](
	[FTCtyCode] [varchar](5) NOT NULL,
	[FTVatCode] [varchar](5) NOT NULL,
	[FNLngID] [bigint] NOT NULL,
	[FTCtyLongitude] [varchar](50) NULL,
	[FTCtyLatitude] [varchar](50) NULL,
	[FTCtyStaUse] [varchar](1) NULL,
	[FTRteIsoCode] [varchar](5) NULL,
	[FTCtyStaCtrlRate] [varchar](1) NULL,
	[FDLastUpdOn] [datetime] NULL,
	[FTLastUpdBy] [varchar](20) NULL,
	[FDCreateOn] [datetime] NULL,
	[FTCreateBy] [varchar](20) NULL,
	[FTCtyRefID] [varchar](5) NULL,
 CONSTRAINT [PK_TCNMCountry] PRIMARY KEY CLUSTERED 
(
	[FTCtyCode] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO

IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FTCtyCode'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสประเทศ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FTCtyCode'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FTVatCode'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสภาษี' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FTVatCode'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FNLngID'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสภาษา' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FNLngID'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FTCtyLongitude'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ตำแหน่งบนแผนที่ แนวตั้ง' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FTCtyLongitude'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FTCtyLatitude'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ตำแหน่งบนแผนที่ แนวนอน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FTCtyLatitude'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FTCtyStaUse'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สถานะใช้งาน 1 : ใช้งาน  อื่น ๆ : ไม่ใช้งาน' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FTCtyStaUse'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FTRteIsoCode'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัส ISO Code (สกุลเงิน)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FTRteIsoCode'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FTCtyStaCtrlRate'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'สถานะควบคุม Exchange rate รายวัน 1:ควบคุม อื่น ๆ :ไม่ควบคุม (Default ไม่ควบคุม)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FTCtyStaCtrlRate'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FDLastUpdOn'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FDLastUpdOn'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FTLastUpdBy'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้ปรับปรุงรายการล่าสุด' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FTLastUpdBy'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FDCreateOn'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'วันที่สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FDCreateOn'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry', N'COLUMN',N'FTCreateBy'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ผู้สร้างรายการ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry', @level2type=N'COLUMN',@level2name=N'FTCreateBy'
GO

/****** Object:  Table [dbo].[TCNMCountry_L]    Script Date: 14/12/2565 11:52:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TCNMCountry_L]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TCNMCountry_L](
	[FTCtyCode] [varchar](5) NOT NULL,
	[FNLngID] [bigint] NOT NULL,
	[FTCtyName] [nvarchar](200) NULL,
	[FTCtyRmk] [nvarchar](200) NULL,
 CONSTRAINT [PK_TCNMCountry_L] PRIMARY KEY CLUSTERED 
(
	[FTCtyCode] ASC,
	[FNLngID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO

IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry_L', N'COLUMN',N'FTCtyCode'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสประเทศ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry_L', @level2type=N'COLUMN',@level2name=N'FTCtyCode'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry_L', N'COLUMN',N'FNLngID'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'รหัสภาษา' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry_L', @level2type=N'COLUMN',@level2name=N'FNLngID'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry_L', N'COLUMN',N'FTCtyName'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ชื่อ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry_L', @level2type=N'COLUMN',@level2name=N'FTCtyName'
GO
IF NOT EXISTS (SELECT * FROM sys.fn_listextendedproperty(N'MS_Description' , N'SCHEMA',N'dbo', N'TABLE',N'TCNMCountry_L', N'COLUMN',N'FTCtyRmk'))
	EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'หมายเหตุ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'TCNMCountry_L', @level2type=N'COLUMN',@level2name=N'FTCtyRmk'
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TCNTAutoHisTxn' AND COLUMN_NAME = 'FTSatUsrNum') BEGIN
	ALTER TABLE TCNTAutoHisTxn ADD FTSatUsrNum VARCHAR(50) 
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TCNTAutoHisTxn' AND COLUMN_NAME = 'FTAhmFmtReset') BEGIN
	ALTER TABLE TCNTAutoHisTxn ADD FTAhmFmtReset VARCHAR(100) 
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TCNTAutoHisTxn' AND COLUMN_NAME = 'FTAhmFmtPst') BEGIN
	ALTER TABLE TCNTAutoHisTxn ADD FTAhmFmtPst VARCHAR(100) 
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TCNTAutoHisTxn' AND COLUMN_NAME = 'FTSatStaAlwSep') BEGIN
	ALTER TABLE TCNTAutoHisTxn ADD FTSatStaAlwSep VARCHAR(1) 
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TCNTAutoHisTxn' AND COLUMN_NAME = 'FNAhmNumSize') BEGIN
	ALTER TABLE TCNTAutoHisTxn ADD FNAhmNumSize INT 
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TCNTPdtPmtHD' AND COLUMN_NAME = 'FTPmhStaAlwDis') BEGIN
	ALTER TABLE TCNTPdtPmtHD ADD FTPmhStaAlwDis VARCHAR(1) 
END
GO

/****** Object:  Table [dbo].[TCNMPdtSpcZone]    Script Date: 16/12/2565 10:35:44 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[TCNMPdtSpcZone]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[TCNMPdtSpcZone](
	[FTPdtCode] [varchar](5) NOT NULL,
	[FTZneCode] [varchar](20) NOT NULL,
	[FTPdtStaInOrEx] [varchar](1) NOT NULL,
	[FDLastUpdOn] [datetime] NULL,
	[FTLastUpdBy] [varchar](20) NULL,
	[FDCreateOn] [datetime] NULL,
	[FTCreateBy] [varchar](20) NULL,
 CONSTRAINT [PK_TCNMPdtSpcZone] PRIMARY KEY CLUSTERED 
(
	[FTPdtCode] ASC,
	[FTZneCode] ASC,
	[FTPdtStaInOrEx] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TsysMasTmp' AND COLUMN_NAME = 'FTZneCode') BEGIN
	ALTER TABLE TsysMasTmp ADD FTZneCode VARCHAR(20) 
END
GO

IF NOT EXISTS(SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = 'TsysMasTmp' AND COLUMN_NAME = 'FTPdtStaInOrEx') BEGIN
	ALTER TABLE TsysMasTmp ADD FTPdtStaInOrEx VARCHAR(1) 
END
GO