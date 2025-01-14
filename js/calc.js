document.addEventListener("DOMContentLoaded", function () {
  const loanAmountInput = document.getElementById("loan-amount");
  const loanTermInput = document.getElementById("loan-term");
  const interestRateInput = document.getElementById("interest-rate");
  const addRepayInput = document.getElementById("add-repay");
  const repayFreqInput = document.getElementById("repay-freq");

  const monthlyPaymentOutput = document.getElementById("repay-amount");
  const totalRepaymentOutput = document.getElementById("total-repayment");
  const totalInterestOutput = document.getElementById("total-interest");
  const variableRateOutput = document.getElementById("variable-rate");

  function calculateLoan() {
    const principal = parseFloat(loanAmountInput.value);
    const termInYears = parseFloat(loanTermInput.value);
    const interestRate = parseFloat(interestRateInput.value);
    const additionalRepay = parseFloat(addRepayInput.value) || 0;
    const repaymentFrequency = repayFreqInput.value;

    // Validate required fields
    if (isNaN(principal) || isNaN(termInYears) || isNaN(interestRate)) {
      // Show default values when required fields are missing
      monthlyPaymentOutput.textContent = "--";
      totalRepaymentOutput.textContent = "--";
      totalInterestOutput.textContent = "--";
      variableRateOutput.textContent = isNaN(interestRate)
        ? "--"
        : `${interestRate.toFixed(2)}%`;
      return;
    }

    // Convert annual interest rate to monthly/weekly/fortnightly rate
    let paymentsPerYear;
    if (repaymentFrequency === "monthly") paymentsPerYear = 12;
    else if (repaymentFrequency === "fortnightly") paymentsPerYear = 26;
    else if (repaymentFrequency === "weekly") paymentsPerYear = 52;

    const ratePerPeriod = interestRate / 100 / paymentsPerYear;
    const totalPayments = termInYears * paymentsPerYear;

    // Calculate periodic payment
    const x = Math.pow(1 + ratePerPeriod, totalPayments);
    const baseRepayment =
      ratePerPeriod > 0
        ? (principal * x * ratePerPeriod) / (x - 1)
        : principal / totalPayments;

    const totalRepayment =
      baseRepayment * totalPayments + additionalRepay * totalPayments;
    const totalInterest = totalRepayment - principal;

    // Update the results
    monthlyPaymentOutput.textContent = `$${(
      baseRepayment + additionalRepay
    ).toFixed(2)}`;
    totalRepaymentOutput.textContent = `$${totalRepayment.toFixed(2)}`;
    totalInterestOutput.textContent = `$${totalInterest.toFixed(2)}`;
    variableRateOutput.textContent = `${interestRate.toFixed(2)}%`;
  }

  // Attach event listeners to inputs for real-time updates
  [
    loanAmountInput,
    loanTermInput,
    interestRateInput,
    addRepayInput,
    repayFreqInput,
  ].forEach((input) => {
    input.addEventListener("input", calculateLoan);
  });

  // Initial display
  calculateLoan();
});
