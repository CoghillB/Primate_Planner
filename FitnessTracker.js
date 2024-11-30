function calculateCaloriesBurned(weight, duration, activityType) {
  const workOutValues = {
      cardio: 8.0,
      weightLifting: 5.0
  };
  // Calculate the calories burned using
  // Calories Burned (min/lbs)=MET×0.0175×Weight (lbs)×Time (minutes)
  const effortValue = workOutValues[activityType];
  return (effortValue * 0.0175 * weight * duration);
}

//Function to update daily exercise

function updateDailyExercise(event){
    const weight = parseFloat(document.getElementById('weight').value);
    const activityType = parseFloat(document.getElementById('duration').value).toLowerCase();
    const duration = parseFloat(document.getElementById('activityType').value);

    const caloriesBurned = calculateCaloriesBurned(weight, duration, activityType);
    document.getElementById('calories').value = caloriesBurned.toFixed(2);

    alert(You burned ${caloriesBurned.toFixed(2)} calories during this exercise);
}

//this will take an array of weights and return the average weight forr the week
function weeklyAverageWeight(weights){
    const totalWeight = weights.reduce((sum, weight) => sum + weight, 0);
    return (totalWeight / weights.length).toFixed(2);
}

//function to keep track of our goals
function trackGoals(currentCalories, weeklyCalories, currentWeight, goalWeight){
    const calorieProgress =((currentCalories / weeklyCalories) * 100).toFixed(2);
    const weightProgress = ((currentWeight / goalWeight) * 100).toFixed(2);
    return{
        calorieProgress,
        weightProgress
    };
}
document.getElementById('dailySpread').addEventListener('submit', updateDailyExercise);

