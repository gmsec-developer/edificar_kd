using Edificar.KDBridge.Models;
using Edificar.KDBridge.Services;

var document = new EdificarImportDocument();

document.Header = new
{
    project_name = "Proyecto Demo EDIFICAR",
    client_name = "Cliente Demo",
    designer = "Mauricio",
    currency = "USD",
    total_without_tax = 1200.50,
    total_with_tax = 1380.58
};

document.Modules.Add(new ModuleItem
{
    Number = 98,
    Reference = "1BF- 59",
    Description = "Modulo Bajo Fregadero",
    Quantity = 1,
    Dx = 590,
    Dy = 520,
    Dz = 450,
    Pvp = 320,
    Total = 320
});

document.Parts.Add(new PartItem
{
    LineNumber = 825,
    ModuleNumber = 98,
    ModuleReference = "1BF- 59",
    TypeCode = 2,
    Part = "PUER 111",
    Quantity = 2,
    Length = 289,
    Width = 444,
    Thickness = 19,
    MaterialCode = "01007",
    MaterialRaw = "DURAPLAC NOVOKOR 19 mm _N25005 MILAN",
    Material = "DURAPLAC NOVOKOR 19 mm",
    ColorCode = "N25005",
    ColorDescription = "MILAN"
});

var exporter = new JsonExportService();

var output = Path.Combine(
    Directory.GetCurrentDirectory(),
    "..",
    "exports",
    "edificar_import_demo.json"
);

await exporter.ExportAsync(document, output);

Console.WriteLine("JSON GENERADO:");
Console.WriteLine(output);
