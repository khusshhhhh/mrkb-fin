document.addEventListener("DOMContentLoaded", () => {
  const loanAmount = document.getElementById("loan-amount");
  const loanTerm = document.getElementById("loan-term");
  const interestRate = document.getElementById("interest-rate");
  const extraRepayment = document.getElementById("extra-repayment");

  const monthlyPayment = document.getElementById("monthly-payment");
  const totalRepayment = document.getElementById("total-repayment");
  const totalInterest = document.getElementById("total-interest");

  function calculateLoan() {
    const principal = parseFloat(loanAmount.value);
    const annualInterest = parseFloat(interestRate.value) / 100;
    const monthlyInterest = annualInterest / 12;
    const payments = parseInt(loanTerm.value) * 12;
    const extra = parseFloat(extraRepayment.value);

    const x = Math.pow(1 + monthlyInterest, payments);
    const monthly = (principal * x * monthlyInterest) / (x - 1);

    if (isFinite(monthly)) {
      const totalLoanRepayment = (monthly + extra) * payments;
      const totalInterestPaid = totalLoanRepayment - principal;

      monthlyPayment.textContent = `$${(monthly + extra).toFixed(2)}`;
      totalRepayment.textContent = `$${totalLoanRepayment.toFixed(2)}`;
      totalInterest.textContent = `$${totalInterestPaid.toFixed(2)}`;
    } else {
      monthlyPayment.textContent = "$0";
      totalRepayment.textContent = "$0";
      totalInterest.textContent = "$0";
    }
  }

  loanAmount.addEventListener("input", calculateLoan);
  loanTerm.addEventListener("input", calculateLoan);
  interestRate.addEventListener("input", calculateLoan);
  extraRepayment.addEventListener("input", calculateLoan);

  calculateLoan();
});
