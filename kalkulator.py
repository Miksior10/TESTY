def kalkulator():
    print("Witaj w prostym kalkulatorze!")
    print("Wybierz operację:")
    print("1. Dodawanie")
    print("2. Odejmowanie")
    print("3. Mnożenie")
    print("4. Dzielenie")
    
    wybor = input("Wpisz numer operacji (1/2/3/4): ")

    if wybor in ['1', '2', '3', '4']:
        liczba1 = float(input("Podaj pierwszą liczbę: "))
        liczba2 = float(input("Podaj drugą liczbę: "))

        if wybor == '1':
            wynik = liczba1 + liczba2
            print(f"Wynik: {liczba1} + {liczba2} = {wynik}")
        elif wybor == '2':
            wynik = liczba1 - liczba2
            print(f"Wynik: {liczba1} - {liczba2} = {wynik}")
        elif wybor == '3':
            wynik = liczba1 * liczba2
            print(f"Wynik: {liczba1} * {liczba2} = {wynik}")
        elif wybor == '4':
            if liczba2 != 0:
                wynik = liczba1 / liczba2
                print(f"Wynik: {liczba1} / {liczba2} = {wynik}")
            else:
                print("Błąd: nie można dzielić przez zero.")
    else:
        print("Nieprawidłowy wybór. Spróbuj ponownie.")

# Uruchomienie kalkulatora
kalkulator()
