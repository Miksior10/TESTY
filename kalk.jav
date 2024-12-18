import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class SimpleCalculator {

    public static void main(String[] args) {
        // Tworzenie ramki
        JFrame frame = new JFrame("Kalkulator");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(300, 400);
        frame.setLayout(new BorderLayout());

        // Pole tekstowe na wynik
        JTextField resultField = new JTextField();
        resultField.setFont(new Font("Arial", Font.BOLD, 24));
        resultField.setHorizontalAlignment(JTextField.RIGHT);
        resultField.setEditable(false);
        frame.add(resultField, BorderLayout.NORTH);

        // Panel na przyciski
        JPanel buttonPanel = new JPanel();
        buttonPanel.setLayout(new GridLayout(4, 4, 5, 5));

        // Przyciski kalkulatora
        String[] buttons = {
                "7", "8", "9", "/",
                "4", "5", "6", "*",
                "1", "2", "3", "-",
                "0", "C", "=", "+"
        };

        // Zmienna przechowująca operację i wartości
        final String[] currentOperation = {""};
        final double[] currentValue = {0};

        // Dodawanie przycisków do panelu
        for (String button : buttons) {
            JButton btn = new JButton(button);
            btn.setFont(new Font("Arial", Font.BOLD, 20));
            buttonPanel.add(btn);

            // Obsługa zdarzeń dla każdego przycisku
            btn.addActionListener(new ActionListener() {
                @Override
                public void actionPerformed(ActionEvent e) {
                    String command = e.getActionCommand();

                    if (command.matches("\\d")) { // Liczby
                        resultField.setText(resultField.getText() + command);
                    } else if (command.equals("C")) { // Reset
                        resultField.setText("");
                        currentValue[0] = 0;
                        currentOperation[0] = "";
                    } else if (command.equals("=")) { // Wynik
                        try {
                            double secondValue = Double.parseDouble(resultField.getText());
                            switch (currentOperation[0]) {
                                case "+":
                                    currentValue[0] += secondValue;
                                    break;
                                case "-":
                                    currentValue[0] -= secondValue;
                                    break;
                                case "*":
                                    currentValue[0] *= secondValue;
                                    break;
                                case "/":
                                    if (secondValue != 0) {
                                        currentValue[0] /= secondValue;
                                    } else {
                                        resultField.setText("Error");
                                        return;
                                    }
                                    break;
                            }
                            resultField.setText(String.valueOf(currentValue[0]));
                            currentOperation[0] = "";
                        } catch (NumberFormatException ex) {
                            resultField.setText("Error");
                        }
                    } else { // Operacje matematyczne
                        try {
                            currentValue[0] = Double.parseDouble(resultField.getText());
                            currentOperation[0] = command;
                            resultField.setText("");
                        } catch (NumberFormatException ex) {
                            resultField.setText("Error");
                        }
                    }
                }
            });
        }

        frame.add(buttonPanel, BorderLayout.CENTER);

        // Wyświetlanie ramki
        frame.setVisible(true);
    }
}
