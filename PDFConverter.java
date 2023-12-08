import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;
import org.apache.pdfbox.pdmodel.PDPage;
import org.apache.pdfbox.pdmodel.PDPageContentStream;

import java.io.File;
import java.io.FileWriter;
import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

public class PDFConverter {
    public static void main(String[] args) {
        if (args.length != 3) {
            System.err.println("Usage: PDFConverter <inputFile> <outputFile> <conversionType>");
            System.exit(1);
        }

        String inputFile = args[0];
        String outputFile = args[1];
        String conversionType = args[2].toLowerCase();

        try {
            switch (conversionType) {
                case "pdf2txt":
                    convertPDFToText(inputFile, outputFile);
                    break;
                case "txt2pdf":
                    convertTextToPDF(inputFile, outputFile);
                    break;
                default:
                    System.err.println("Unsupported conversion type.");
                    System.exit(1);
            }
            System.out.println("Conversion complete.");
        } catch (IOException e) {
            e.printStackTrace();
            System.exit(1);
        }
    }

    private static void convertPDFToText(String pdfFile, String txtFile) throws IOException {
        try (PDDocument document = PDDocument.load(new File(pdfFile))) {
            PDFTextStripper stripper = new PDFTextStripper();
            String text = stripper.getText(document);

            try (FileWriter writer = new FileWriter(txtFile)) {
                writer.write(text);
            }
        }
    }

    private static void convertTextToPDF(String txtFile, String pdfFile) throws IOException {
        try (PDDocument document = new PDDocument()) {
            PDPage page = new PDPage();
            document.addPage(page);

            try (BufferedReader br = new BufferedReader(new FileReader(txtFile));
                 PDPageContentStream contentStream = new PDPageContentStream(document, page)) {

                String line;
                float yPosition = 700; // Starting y position for text
                while ((line = br.readLine()) != null) {
                    contentStream.beginText();
                    contentStream.newLineAtOffset(20, yPosition);
                    contentStream.showText(line);
                    contentStream.endText();
                    yPosition -= 12; // Adjust y position for the next line
                }
            }

            document.save(pdfFile);
        }
    }
}